<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\DBAL\Logging;

use WappoVendor\Doctrine\DBAL\Driver as DriverInterface;
use WappoVendor\Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;
use WappoVendor\Psr\Log\LoggerInterface;
final class Driver extends AbstractDriverMiddleware
{
    /** @var LoggerInterface */
    private $logger;
    /**
     * @internal This driver can be only instantiated by its middleware.
     */
    public function __construct(DriverInterface $driver, LoggerInterface $logger)
    {
        parent::__construct($driver);
        $this->logger = $logger;
    }
    /**
     * {@inheritDoc}
     */
    public function connect(array $params)
    {
        $this->logger->info('Connecting with parameters {params}', ['params' => $this->maskPassword($params)]);
        return new Connection(parent::connect($params), $this->logger);
    }
    /**
     * @param array<string,mixed> $params Connection parameters
     *
     * @return array<string,mixed>
     */
    private function maskPassword(array $params) : array
    {
        if (isset($params['password'])) {
            $params['password'] = '<redacted>';
        }
        if (isset($params['url'])) {
            $params['url'] = '<redacted>';
        }
        return $params;
    }
}
