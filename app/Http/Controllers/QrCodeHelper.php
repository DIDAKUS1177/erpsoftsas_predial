<?php

namespace App\Http\Controllers;

class QrCodeHelper
{
    /**
     * Generate QR code using available library or Google Charts API as fallback
     */
    public static function generateQrCode($url, $size = 100)
    {
        // Try alternative Google Charts URL format first
        try {
            $qrCodeData = self::generateQrCodeAlternative($url, $size);
            if ($qrCodeData) {
                return $qrCodeData;
            }
        } catch (\Exception $e) {
            error_log("QR Code generation error with alternative method: " . $e->getMessage());
        }

        // Try Milon Barcode library (if available)
        if (class_exists('\Milon\Barcode\DNS2D')) {
            try {
                $generator = new \Milon\Barcode\DNS2D();
                $pngData = $generator->getBarcodePNG($url, 'QRCODE', $size/30, $size/30);
                if ($pngData && !empty($pngData)) {
                    return base64_encode($pngData);
                }
            } catch (\Exception $e) {
                error_log("QR Code generation error with Milon: " . $e->getMessage());
            }
        }

        // Try SimpleSoftwareIO QR Code (if available)
        if (class_exists('\SimpleSoftwareIO\QrCode\Generator')) {
            try {
                $generator = new \SimpleSoftwareIO\QrCode\Generator();
                $qrData = $generator->format('png')->size($size)->generate($url);
                if ($qrData && !empty($qrData)) {
                    return base64_encode($qrData);
                }
            } catch (\Exception $e) {
                error_log("QR Code generation error with SimpleSoftwareIO: " . $e->getMessage());
            }
        }

        // Try Endroid QR Code as alternative
        if (class_exists('\Endroid\QrCode\QrCode')) {
            try {
                $qrCode = new \Endroid\QrCode\QrCode($url);
                $qrCode->setSize($size);
                $qrCode->setMargin(10);

                $writer = new \Endroid\QrCode\Writer\PngWriter();
                $result = $writer->write($qrCode);

                return base64_encode($result->getString());
            } catch (\Exception $e) {
                error_log("QR Code generation error with Endroid: " . $e->getMessage());
            }
        }

        // Fallback to Google Charts API (requires internet)
        return self::generateQrCodeWithGoogleCharts($url, $size);
    }

    /**
     * Generate QR code using cURL with better error handling
     */
    private static function generateQrCodeAlternative($url, $size = 100)
    {
        // Try QR-Server.com API as alternative
        $qrServerUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . urlencode($url);

        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $qrServerUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $imageData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($httpCode == 200 && $imageData && !empty($imageData) && empty($error)) {
                // Validate image data
                if (substr($imageData, 0, 8) === "\x89\x50\x4e\x47\x0d\x0a\x1a\x0a") {
                    return base64_encode($imageData);
                }
            }
        }

        return false;
    }

    /**
     * Generate QR code using Google Charts API as fallback
     */
    private static function generateQrCodeWithGoogleCharts($url, $size = 100)
    {
        try {
            $googleChartsUrl = "https://chart.googleapis.com/chart?chs={$size}x{$size}&cht=qr&chl=" . urlencode($url);

            $context = stream_context_create([
                "http" => [
                    "timeout" => 15,
                    "ignore_errors" => true,
                    "method" => "GET",
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n"
                ],
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true
                ]
            ]);

            $imageData = file_get_contents($googleChartsUrl, false, $context);

            if ($imageData !== false && !empty($imageData)) {
                // Verify it's actually image data
                $imageInfo = getimagesizefromstring($imageData);
                if ($imageInfo !== false) {
                    return base64_encode($imageData);
                }
            }
        } catch (\Exception $e) {
            error_log("QR Code generation error with Google Charts: " . $e->getMessage());
        }

        // Final fallback - return placeholder
        return self::generatePlaceholderQr($size);
    }

    /**
     * Generate a placeholder QR code (simple text-based)
     */
    private static function generatePlaceholderQr($size = 100)
    {
        // Create a simple placeholder image with specified size
        $img = imagecreate($size, $size);
        $bgColor = imagecolorallocate($img, 255, 255, 255);
        $textColor = imagecolorallocate($img, 0, 0, 0);
        $borderColor = imagecolorallocate($img, 0, 0, 0);

        // Draw border
        imagerectangle($img, 0, 0, $size-1, $size-1, $borderColor);

        // Add text based on size
        if ($size >= 80) {
            imagestring($img, 2, $size/2-15, $size/2-20, 'QR', $textColor);
            imagestring($img, 1, $size/2-25, $size/2, 'PLACEHOLDER', $textColor);
        } else {
            imagestring($img, 1, $size/2-10, $size/2-5, 'QR', $textColor);
        }

        ob_start();
        imagepng($img);
        $imageData = ob_get_contents();
        ob_end_clean();
        imagedestroy($img);

        return base64_encode($imageData);
    }
}