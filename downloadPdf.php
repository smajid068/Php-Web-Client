<?php
function downloadPdf($downloadUrl)
{
    $ch = curl_init($downloadUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $pdfData = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "cURL Error during PDF download: " . curl_error($ch);
            curl_close($ch);
            return false;
        }

        $fileInfo = pathinfo($downloadUrl);

        $fileName = $fileInfo['basename'];  // Full file name (e.g., ConvertedDocument.pdf)
        $fileBaseName = $fileInfo['filename']; // File name without extension (e.g., ConvertedDocument)
        $fileExtension = $fileInfo['extension']; // File extension (e.g., pdf)

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode === 200) {
            // Save the PDF locally
            $savePath = "Downloads/{$fileBaseName}.{$fileExtension}";
            file_put_contents($savePath, $pdfData);
            echo "PDF downloaded and saved to $savePath\n";
        } else {
            echo "Failed to download PDF. HTTP Code: $httpCode\n";
        }

        curl_close($ch); 
}