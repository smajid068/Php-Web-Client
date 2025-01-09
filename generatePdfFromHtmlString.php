<?php
function generatePdfFromHtmlString($htmlContent, $token)
{
    $url = "https://localhost:5001/api/pdf/generatePdfFromHtmlString";
    $data = json_encode([
        "HtmlContent" => $htmlContent
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode == 200) {
        $responseData = json_decode($response, true);
        $filePath = $responseData['filePath'];
        echo "PDF generated successfully and saved in: {$filePath}\n";

        return $filePath;
        
    } else {
        echo "Failed to generate PDF. HTTP Code: $httpCode\n";
        echo "Response: $response\n";
    }

    curl_close($ch);
}