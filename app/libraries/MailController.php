<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController extends Controller
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->setupMailer();
    }

    private function setupMailer()
    {
        $this->mailer->isSMTP(); // Set mailer to use SMTP
        $this->mailer->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $this->mailer->SMTPAuth = true; // Enable SMTP authentication
        $this->mailer->Username = 'freak.ghost11@gmail.com'; // Your Gmail address
        $this->mailer->Password = 'vqfg hoix cplr vmjg'; // Use the App Password generated above
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $this->mailer->Port = 587; // TCP port to connect to

        // Optional: Set the sender's email and name
        $this->mailer->setFrom('freak.ghost11@gmail.com', 'Freak Products Pvt Ltd');
    }


    public function sendTransactionEmail($userEmail, $username, $orderId, $totalAmount, $paymentMethod, $selectedItems)
    {
        // Prepare the transaction details message with external image links
        $transactionDetails = "
    <table width='100%' cellpadding='0' cellspacing='0' style='background-color:#f4f4f4; padding: 20px;'>
        <tr>
            <td style='text-align:center; padding: 20px 0;'>
                <img src='https://w7.pngwing.com/pngs/1012/770/png-transparent-amazon-logo-amazon-com-amazon-video-logo-company-brand-amazon-logo-miscellaneous-wish-text.png' alt='Company Logo' style='max-width: 200px;' />
            </td>
        </tr>
        <tr>
            <td style='background-color:#ffffff; padding: 20px; border-radius: 8px;'>
                <h2 style='color:#333333;'>Order Confirmation</h2>
                <p style='font-size: 16px; color:#555555;'>Dear <strong>$username</strong>,</p>
                <p style='font-size: 16px; color:#555555;'>Thank you for your order! We are pleased to inform you that your payment of <strong>Rs. $totalAmount</strong> has been processed successfully.</p>
                <p style='font-size: 16px; color:#555555;'>Here are the details of your order:</p>
                <hr style='border:1px solid #e0e0e0; margin:20px 0;'>
                
                <!-- Order Details Table -->
                <table style='width: 100%; border-collapse: collapse;'>
                    <thead>
                        <tr style='background-color:#f8f8f8;'>
                            <th style='padding: 8px; text-align:left; font-size: 14px; color: #333333;'>Order ID</th>
                            <th style='padding: 8px; text-align:left; font-size: 14px; color: #333333;'>Total Amount</th>
                            <th style='padding: 8px; text-align:left; font-size: 14px; color: #333333;'>Status</th>
                            <th style='padding: 8px; text-align:left; font-size: 14px; color: #333333;'>Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style='padding: 8px; text-align:left; font-size: 14px; color:#333333;'>#$orderId</td>
                            <td style='padding: 8px; text-align:left; font-size: 14px; color:#333333;'>Rs. $totalAmount</td>
                            <td style='padding: 8px; text-align:left; font-size: 14px; color:#333333;'>Completed</td>
                            <td style='padding: 8px; text-align:left; font-size: 14px; color:#333333;'>$paymentMethod</td>
                        </tr>
                    </tbody>
                </table>

                <p style='font-size: 16px; color:#555555;'><strong>Order Items:</strong></p>

                <!-- Order Items Table -->
                <table style='width: 100%; border-collapse: collapse;'>
                    <thead>
                        <tr style='background-color:#f8f8f8;'>
                            <th style='padding: 8px; text-align:left; font-size: 14px; color: #333333;'>Product Name</th>
                            <th style='padding: 8px; text-align:left; font-size: 14px; color: #333333;'>Price</th>
                            <th style='padding: 8px; text-align:left; font-size: 14px; color: #333333;'>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>";

        // Loop through selected items and display them in the table
        foreach ($selectedItems as $item) {
            $transactionDetails .= "
        <tr>
            <td style='padding: 8px; font-size: 14px; color:#333333;'>{$item['productName']}</td>
            <td style='padding: 8px; font-size: 14px; color:#333333;'>Rs. {$item['sellingPrice']}</td>
            <td style='padding: 8px; font-size: 14px; color:#333333;'>{$item['quantity']}</td>
        </tr>";
        }

        // Close the order items table
        $transactionDetails .= "</tbody></table>";

        // Add payment method logo
        if ($paymentMethod == 'paypal') {
            $transactionDetails .= "
        <hr style='border:1px solid #e0e0e0; margin:20px 0;'>
        <p style='font-size: 16px; color:#555555;'>Paid via PayPal</p>
        <table border='0' cellpadding='1' cellspacing='0' style='width: 100%;'>
            <tr><td style='text-align:center;'>
                <a href='https://www.paypal.com/webapps/mpp/paypal-popup' title='How PayPal Works' onclick='javascript:window.open(\"https://www.paypal.com/webapps/mpp/paypal-popup\",\"WIPaypal\",\"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700\"); return false;'>
                    <img src='https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg' alt='PayPal Acceptance Mark' style='max-width: 200px;'>
                </a>
            </td></tr>
        </table>";
        } elseif ($paymentMethod == 'stripe') {
            $transactionDetails .= "
        <hr style='border:1px solid #e0e0e0; margin:20px 0;'>
        <p style='font-size: 16px; color:#555555;'>Paid via Stripe</p>
        <table border='0' cellpadding='1' cellspacing='0' style='width: 100%;'>
            <tr><td style='text-align:center;'>
                <img src='https://media.designrush.com/inspiration_images/656402/conversions/3-desktop.jpg' alt='Stripe Logo' style='max-width: 100%; height: auto;'>
            </td></tr>
        </table>";
        }

        // Closing email content
        $transactionDetails .= "
        <hr style='border:1px solid #e0e0e0; margin:20px 0;'>
        <p style='font-size: 16px; color:#555555;'>You will receive an email confirmation shortly with more details regarding the shipping of your order.</p>
        <p style='font-size: 16px; color:#555555;'>If you have any questions or need further assistance, feel free to contact us at <a href='mailto:support@freakproducts.com'>support@freakproducts.com</a>.</p>
        <p style='font-size: 16px; color:#555555;'>Thank you for choosing Freak Products!</p>
        <p style='font-size: 16px; color:#555555;'>Best regards,</p>
        <p style='font-size: 16px; color:#555555;'><strong>The Freak Products Team</strong></p>
    </td>
</tr>
</table>";

        // Send email using your mailer object
        try {
            $this->mailer->addAddress($userEmail);
            $this->mailer->Subject = 'Order Confirmation';

            // Set the email format to HTML
            $this->mailer->isHTML(true);

            // Set the body of the email
            $this->mailer->Body = $transactionDetails;

            // Send the email
            $this->mailer->send();
        } catch (Exception $e) {
            echo "Email could not be sent: {$this->mailer->ErrorInfo}";
        }
    }

    public function sendEmail($to, $subject, $username, $orderId, $cartItems)
    {
        try {
            $this->setupMailer(); // Setup PHPMailer configuration

            // Add recipient
            $this->mailer->addAddress($to);

            // Set email format to HTML
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;

            // Email Header with company logo and greeting
            $message = "<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#f4f4f4; padding: 20px;'>
            <tr>
                <td style='text-align:center; padding: 20px 0;'>
                    <img src='https://w7.pngwing.com/pngs/1012/770/png-transparent-amazon-logo-amazon-com-amazon-video-logo-company-brand-amazon-logo-miscellaneous-wish-text.png' alt='Company Logo' style='max-width: 200px;' />
                </td>
            </tr>
            <tr>
                <td style='background-color:#ffffff; padding: 20px; border-radius: 8px;'>
                    <h2 style='color:#333333;'>Order Cancellation Notification</h2>
                    <p style='font-size: 16px; color:#555555;'>Dear <strong>$username</strong>,</p>
                    <p style='font-size: 16px; color:#555555;'>We regret to inform you that your order with Order ID: <strong>#{$orderId}</strong> has been canceled due to inactivity.</p>
                    <p style='font-size: 16px; color:#555555;'>Please find the details of the canceled items below:</p>

                    <table style='width: 100%; border-collapse: collapse;'>
                        <thead>
                            <tr style='background-color:#f8f8f8;'>
                                <th style='padding: 8px; text-align:left; font-size: 14px; color: #333333;'>Product Name</th>
                                <th style='padding: 8px; text-align:left; font-size: 14px; color: #333333;'>Quantity</th>
                                <th style='padding: 8px; text-align:left; font-size: 14px; color: #333333;'>Price</th>
                            </tr>
                        </thead>
                        <tbody>";

            // Loop through selected items to display ordered products that were canceled
            foreach ($cartItems as $item) {
                $message .= "
            <tr>
                <td style='padding: 8px; font-size: 14px; color:#333333;'>{$item->productName}</td>
                <td style='padding: 8px; font-size: 14px; color:#333333;'>{$item->quantity}</td>
                <td style='padding: 8px; font-size: 14px; color:#333333;'>Rs. {$item->totalAmount}</td>
            </tr>";
            }

            $message .= "</tbody></table>";

            // Total Amount section
            $message .= "
            <p style='font-size: 16px; color:#555555;'><strong>Total Amount: Rs. {$item->totalAmount}</strong></p>
            <p style='font-size: 16px; color:#555555;'>We apologize for the inconvenience caused by this cancellation. If you have any questions or concerns, please do not hesitate to contact our customer support team.</p>
            <p style='font-size: 16px; color:#555555;'>Thank you for shopping with us!</p>
            <br/>
            <p style='font-size: 16px; color:#555555;'>Best regards,</p>
            <p style='font-size: 16px; color:#555555;'><strong>Freak Products Pvt Ltd</strong></p>
            <p style='font-size: 14px; color:#888888;'>If you need assistance, feel free to <a href='mailto:freak.ghost11@gmail.com'>contact support</a>.</p>
        </td>
    </tr>
</table>";

            // Set the body and send the email
            $this->mailer->Body = $message;
            $this->mailer->send();
            echo 'Message has been sent successfully';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}";
        }
    }
}
