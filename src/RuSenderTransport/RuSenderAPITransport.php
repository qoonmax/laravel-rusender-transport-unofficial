<?php

namespace Qoonmax\RuSenderApiMailTransport\RuSenderTransport;

use Psr\Log\LoggerInterface;
use Qoonmax\RuSenderApiMailTransport\RuSenderTransport\Exceptions\TooManyRecipients;
use Qoonmax\RuSenderApiMailTransport\RuSenderTransport\Exceptions\TooManySenders;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\HttpTransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractApiTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class RuSenderAPITransport extends AbstractApiTransport
{
    /**
     * @var string
     */
    protected string $apiKey;

    public function __construct(
        HttpClientInterface $client = null,
        EventDispatcherInterface $dispatcher = null,
        LoggerInterface $logger = null
    ) {
        parent::__construct($client, $dispatcher, $logger);
    }

    /** Set the API key.
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey(#[\SensitiveParameter] string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /** Send the mail via API.
     * @param SentMessage $sentMessage
     * @param Email $email
     * @param Envelope $envelope
     * @return ResponseInterface
     * @throws TooManyRecipients
     * @throws TooManySenders
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    protected function doSendApi(SentMessage $sentMessage, Email $email, Envelope $envelope): ResponseInterface
    {
        if (count($email->getTo()) > 1) {
            throw new TooManyRecipients('RuSender API supports only one recipient');
        }

        if (count($email->getFrom()) > 1) {
            throw new TooManySenders('RuSender API supports only one sender');
        }

        $response = $this->client->request('POST', 'https://' . $this->getEndpoint() . '/api/v1/external-mails/send', [
            'headers' => [
                'Accept' => 'application/json',
                'x-api-key' => $this->apiKey,
            ],
            'json' => [
                'mail' => [
                    'to' => $this->arrayMap($email->getTo()[0]),
                    'from' => $this->arrayMap($email->getFrom()[0]),
                    'subject' => $email->getSubject(),
                    'html' => $email->getHtmlBody()
                ],
            ],
        ]);

        try {
            $statusCode = $response->getStatusCode();
            $result = $response->toArray(false);
        } catch (DecodingExceptionInterface) {
            throw new HttpTransportException('Unable to send an email: '.$response->getContent(false).sprintf(' (code %d).', $statusCode), $response);
        } catch (TransportExceptionInterface $e) {
            throw new HttpTransportException('Could not reach the remote RuSender server.', $response, 0, $e);
        }

        if (201 !== $statusCode) {
            throw new HttpTransportException('Unable to send an email: '.($result['message'] ?? $response->getContent(false)).sprintf(' (code %d).', $statusCode), $response, $statusCode);
        }

        $sentMessage->setMessageId($result['uuid']);

        return $response;
    }

    /** Map the contacts to name and email.
     * @param Address $address
     * @return array
     */
    protected function arrayMap(Address $address): array
    {
        return [
            'name' => $address->getName(),
            'email' => $address->getAddress(),
        ];
    }

    /** Stringify the endpoint.
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('api://%s', $this->getEndpoint());
    }

    /** Get the endpoint.
     * @return string
     */
    protected function getEndpoint(): string
    {
        return $this->host;
    }
}
