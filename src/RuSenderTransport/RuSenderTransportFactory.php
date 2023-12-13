<?php
namespace Qoonmax\RuSenderApiMailTransport\RuSenderTransport;

use Symfony\Component\Mailer\Exception\UnsupportedSchemeException;
use Symfony\Component\Mailer\Transport\AbstractTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;

final class RuSenderTransportFactory extends AbstractTransportFactory
{
    /** Create a new transport instance.
     * @param Dsn $dsn
     * @return TransportInterface
     */
    public function create(Dsn $dsn): TransportInterface
    {
        if (!in_array($dsn->getScheme(), $this->getSupportedSchemes(), true)) {
            throw new UnsupportedSchemeException($dsn, $dsn->getScheme(), $this->getSupportedSchemes());
        }

        return (new RuSenderAPITransport(
            $this->client,
            $this->dispatcher,
            $this->logger
        ))->setHost($dsn->getHost() === 'default' ? 'api.beta.rusender.ru' : $dsn->getHost())
          ->setApiKey($dsn->getPassword());
    }

    /** Get the supported schemes.
     * @return string[]
     */
    protected function getSupportedSchemes(): array
    {
        return ['api'];
    }
}
