<?php

require_once "auth.php";
require_once "generatePdfFromHtmlString.php";
require_once "downloadPdf.php";
require_once "generatePdfFromHtmlUrl.php";


// Main script execution
$username = "user";
$password = "password";
$htmlContent = "<h1>Hello, Everyone!</h1><p>This is the first secured PDF generation request.</p>";
$webUrl = "https://en.wikipedia.org/wiki/Jensen_Huang";

// Step 1: Get the token
$token = getToken($username, $password);

//a) Step 2: Generate the PDF from HTML string using IronPDF
//$generatedFileDownloadUrl = generatePdfFromHtmlString($htmlContent, $token);

//b) Step 2: Generate the PDF from a Webpage URL using IronPDF
$generatedFileDownloadUrl = generatePdfFromHtmlUrl($webUrl, $token);

//Download the PDF from URL in PHP
downloadPdf($generatedFileDownloadUrl);

