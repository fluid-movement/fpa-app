<?php

namespace App\Services;

use App\Models\Pool;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Exception\ValidationException;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Psr\Log\LoggerInterface;

readonly class PoolQrCodeService
{
    public function __construct(private LoggerInterface $logger) {}

    public function generate(Pool $pool, ?string $label = null): ?ResultInterface
    {
        return $this->build(
            data: route('events.division.judge', $pool),
            label: $label ?? 'Pool '.$pool->name
        );
    }

    private function build(string $data, ?string $label): ?ResultInterface
    {
        $builder = new Builder(
            writer: new PngWriter,
            writerOptions: [],
            validateResult: false,
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            labelText: $label ?? $data,
            labelFont: new OpenSans(15),
            labelAlignment: LabelAlignment::Center
        );

        try {
            $result = $builder->build();
        } catch (ValidationException $e) {
            $this->logger->error('QR code generation failed: '.$e->getMessage());

            return null;
        }

        return $result;
    }
}
