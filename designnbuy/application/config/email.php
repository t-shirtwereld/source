<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 |-------------------------------------------------------------------------
 | Name: email.php
 |-------------------------------------------------------------------------
 |
 |-------------------------------------------------------------------------
 */

$config['useragent']    = "CodeIgniter";    // The "user agent". ( None )
$config['protocol']        = "smtp";            // The mail sending protocol. ( mail, sendmail, or smtp )
$config['mailpath']        = "";                // The server path to Sendmail. ( None )

$config['smtp_host']    = "mail.rightwaysolution.com";                // SMTP Server Address. ( None )
$config['smtp_user']    = "test@rightwaysolution.com";                // SMTP Username. ( None )
$config['smtp_pass']    = "Rws@123";                // SMTP Password. ( None )
$config['smtp_port']    = "25";                // SMTP Port. ( None )
$config['smtp_timeout']    = 5;                // SMTP Timeout (in seconds). ( None )

$config['wordwrap']        = TRUE;                // Enable word-wrap. ( TRUE or FALSE (boolean) )
$config['wrapchars']    = "76";                // Character count to wrap at. ( None )

$config['mailtype']        = "html";            // Type of mail. If you send HTML email you must send it as a complete web page. ( text or html )
                                            // Make sure you don't have any relative links or relative image paths otherwise they will not work.

$config['charset']        = "utf-8";            // Character set (utf-8, iso-8859-1, etc.). ( None )
$config['validate']        = FALSE;            // Whether to validate the email address. ( TRUE or FALSE (boolean) )
$config['priority']        = "3";                // Email Priority. 1 = highest. 5 = lowest. 3 = normal. ( 1, 2, 3, 4, 5 )
$config['crlf']            = "\r\n";            // Newline character. (Use "\r\n" to comply with RFC 822). ( "\r\n" or "\n" or "\r" )
$config['newline']        = "\r\n";            // Newline character. (Use "\r\n" to comply with RFC 822). ( "\r\n" or "\n" or "\r" )

$config['bcc_batch_mode']    = FALSE;        // Enable BCC Batch Mode. ( TRUE or FALSE (boolean) )
$config['bcc_batch_size']    = 200;            // Number of emails in each BCC batch. ( None )


// ------------------------------------------------------------------------
/* End of file email.php */
/* Location: ./application/config/email.php */
?>