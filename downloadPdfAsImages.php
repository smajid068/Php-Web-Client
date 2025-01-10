<?php

function downloadPdfAsImages($pdUfrl, $token)
{
    $url = "https://localhost:5001/api/pdf/extractPdfAsImages";
    $data = json_encode([
        "PdfFilePath" => $pdUfrl
    ]);

    // Initialize cURL for the request to fetch the list of download URLs
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token, // Include Bearer token for authentication
        'Content-Type: application/json',
    ]);

    // Execute the request to fetch the response
    $response = curl_exec($ch);

    // Check for errors in the initial request
    if (curl_errno($ch)) {
        echo "Failed to fetch URLs from $url. Error: " . curl_error($ch) . "\n";
        curl_close($ch);
        return;
    }

    // Decode the response (expected to be a JSON list of URLs)
    $responseData = json_decode($response, true);
    $imageFileUrls = $responseData['imageFilePaths'];

    if (!is_array($imageFileUrls)) {
        echo "Failed to parse the response. Expected a JSON array of URLs.\n";
        curl_close($ch);
        return;
    }

    curl_close($ch); // Close the cURL session for the initial request

    // Iterate over each URL in the response and download the file
    foreach ($imageFileUrls as $url) {
        // Extract the file name from the URL
        $fileName = basename(parse_url($url, PHP_URL_PATH));

        // Full path to save the file
        $filePath = "Downloads/{$fileName}";

        // Initialize cURL for the file download
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Execute the download
        $fileData = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200) {
            echo "Failed to download $url. HTTP Code: $httpCode\n";
        } elseif (curl_errno($ch)) {
            echo "Failed to download $url. Error: " . curl_error($ch) . "\n";
        } else {
            // Save the file to the specified directory
            file_put_contents($filePath, $fileData);
            echo "Downloaded $fileName to $filePath\n";
        }

        // Close the cURL session
        curl_close($ch);
    }
}