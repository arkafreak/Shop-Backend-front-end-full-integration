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
        $transactionDetails = "<p>Dear <strong>$username,</strong></p>" .
            "<p>Thank you for your order! We are pleased to inform you that your payment of Rs. $totalAmount has been processed successfully.</p>" .
            "<p>Here are the details of your order:</p>" .
            "<hr>" .

            // Add the order details in a table with center-aligned values
            "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>" .
            "<tr>" .
            "<th style='background-color: #f2f2f2; text-align: center;'>Order ID</th>" .
            "<th style='background-color: #f2f2f2; text-align: center;'>Total Amount</th>" .
            "<th style='background-color: #f2f2f2; text-align: center;'>Status</th>" .
            "<th style='background-color: #f2f2f2; text-align: center;'>Payment Method</th>" .
            "</tr>" .
            "<tr>" .
            "<td style='text-align: center;'>$orderId</td>" .
            "<td style='text-align: center;'>Rs. $totalAmount</td>" .
            "<td style='text-align: center;'>Completed</td>" .
            "<td style='text-align: center;'>$paymentMethod</td>" .
            "</tr>" .
            "</table>" .

            // Add the order items details in a table
            "<p><strong>Order Items:</strong></p>" .
            "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>" .
            "<tr>" .
            "<th style='background-color: #f2f2f2; text-align: center;'>Product Name</th>" .
            "<th style='background-color: #f2f2f2; text-align: center;'>Price</th>" .
            "<th style='background-color: #f2f2f2; text-align: center;'>Quantity</th>" .
            "</tr>";

        // Loop through selected items and display them in the table
        foreach ($selectedItems as $item) {
            $transactionDetails .= "<tr>" .
                "<td style='text-align: center;'>" . $item['productName'] . "</td>" .
                "<td style='text-align: center;'>Rs. " . $item['sellingPrice'] . "</td>" .
                "<td style='text-align: center;'>" . $item['quantity'] . "</td>" .
                "</tr>";
        }

        // Close the order items table
        $transactionDetails .= "</table>" .

            "<p>You will receive an email confirmation shortly with more details regarding the shipping of your order.</p>" .
            "<p>If you have any questions or need further assistance, feel free to contact us at <a href='mailto:freak.ghost11@gmail.com'>support@freakproducts.com</a>.</p>" .
            "<p>Thank you for choosing Freak Products!</p>" .
            "<p>Best Regards,<br><strong>The Freak Products Team</strong></p>";

        // Add payment method logo
        if ($paymentMethod == 'paypal') {
            $transactionDetails .=
                '<table border="0" cellpadding="1" cellspacing="0">' .
                '<tr><td></td></tr>' .
                '<tr><td>' .
                '<a href="https://www.paypal.com/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open(\'https://www.paypal.com/webapps/mpp/paypal-popup\',\'WIPaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700\'); return false;">' .
                '<img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg" border="0" alt="PayPal Acceptance Mark">' .
                '</a>' .
                '</td></tr>' .
                '</table>';
        } elseif ($paymentMethod == 'stripe') {
            $transactionDetails .=
                '<table border="0" cellpadding="1" cellspacing="0">' .
                '<tr><td></td></tr>' .
                '<tr><td>' .
                '<img src="https://media.designrush.com/inspiration_images/656402/conversions/3-desktop.jpg" alt="Stripe Logo" style="width: 100%; height: auto; object-fit: cover;">' .
                '</td></tr>' .
                '</table>';
        }

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
}
