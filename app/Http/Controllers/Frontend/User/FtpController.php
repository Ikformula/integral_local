<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;

class FtpController extends Controller
{
    public function index()
    {
        //
    }

    public function listFiles()
    {
        // FTP Credentials (Move to .env for security)
        $ftpServer = env('FTP_HOST', '35.210.139.128');
        $ftpUsername = env('FTP_USERNAME', 'dumpacct');
        $ftpPassword = env('FTP_PASSWORD', 'ZLcJhKcgMmf4FYrR');
        $ftpPort = env('FTP_PORT', 21); // Default: 21

        // Connect to FTP Server
        $ftpConn = ftp_connect($ftpServer, $ftpPort);
        if (!$ftpConn) {
            return response()->json(['error' => '❌ Could not connect to FTP server.'], 500);
        }

        // Login to FTP
        if (!@ftp_login($ftpConn, $ftpUsername, $ftpPassword)) {
            ftp_close($ftpConn);
            return response()->json(['error' => '❌ Failed to authenticate. Check credentials.'], 500);
        }

        // Enable Passive Mode (Important for many servers)
        ftp_pasv($ftpConn, true);

        // Retrieve file list from the root directory
        $files = ftp_nlist($ftpConn, "split");

        // Close the FTP connection
        ftp_close($ftpConn);

        // Handle errors and empty directory case
        if ($files === false) {
            return response()->json(['error' => '❌ Could not retrieve file list.'], 500);
        }

        if (empty($files)) {
            return response()->json(['message' => '✅ No files found in the FTP directory.']);
        }

        return response()->json(['files' => $files]);
    }

    public function ftpCurl()
    {
        $ftpServer = env('FTP_HOST', '35.210.139.128');
        $ftpUsername = env('FTP_USERNAME', 'dumpacct');
        $ftpPassword = env('FTP_PASSWORD', 'ZLcJhKcgMmf4FYrR');
        $ftpPort = env('FTP_PORT', 21); // Default: 21

        $url = "ftp://$ftpServer/ArikFTP/";
        $userpass = "$ftpUsername:$ftpPassword";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, $userpass);
        curl_setopt($ch, CURLOPT_DIRLISTONLY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return response()->json(['curl_list' => explode("\n", trim($result))]);

    }

}
