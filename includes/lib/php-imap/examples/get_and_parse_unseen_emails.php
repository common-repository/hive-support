<?php

    /**
     * Example: Get and parse all unseen emails with saving their attachments.
     *
     * @author Sebastian Krätzig <info@ts3-tools.info>
     */
    declare(strict_types=1);

    require_once __DIR__.'/../vendor/autoload.php';

    use PhpImap\Exceptions\ConnectionException;
    use PhpImap\Mailbox;

    $mailbox = new Mailbox(
        '{imap.gmail.com:993/imap/ssl}INBOX', // IMAP server and mailbox folder
        'some@gmail.com', // Username for the before configured mailbox
        '*********', // Password for the before configured username
        __DIR__, // Directory, where attachments will be saved (optional)
        'US-ASCII' // Server encoding (optional)
    );

    try {
        $mail_ids = $mailbox->searchMailbox('UNSEEN');
    } catch (ConnectionException $ex) {
        exit('IMAP connection failed: '.$ex->getErrors('first'));
    } catch (Exception $ex) {
        exit('An error occured: '.$ex->getMessage());
    }

    foreach ($mail_ids as $mail_id) {
        echo "+------ P A R S I N G ------+\n";

        $email = $mailbox->getMail(
            $mail_id, // ID of the email, you want to get
            false // Do NOT mark emails as seen (optional)
        );

        echo 'from-name: '.(string) ($email->fromName ?? $email->fromAddress)."\n";
        echo 'from-email: '.(string) $email->fromAddress."\n";
        echo 'to: '.(string) $email->toString."\n";
        echo 'subject: '.(string) $email->subject."\n";
        echo 'message_id: '.(string) $email->messageId."\n";

        echo 'mail has attachments? ';
        if ($email->hasAttachments()) {
            echo "Yes\n";
        } else {
            echo "No\n";
        }

        if (!empty($email->getAttachments())) {
            echo \count($email->getAttachments())." attachements\n";
        }
        if ($email->textHtml) {
            echo "Message HTML:\n".$email->textHtml;
        } else {
            echo "Message Plain:\n".$email->textPlain;
        }

        if (!empty($email->autoSubmitted)) {
            // Mark email as "read" / "seen"
            $mailbox->markMailAsRead($mail_id);
            echo "+------ IGNORING: Auto-Reply ------+\n";
        }

        if (!empty($email_content->precedence)) {
            // Mark email as "read" / "seen"
            $mailbox->markMailAsRead($mail_id);
            echo "+------ IGNORING: Non-Delivery Report/Receipt ------+\n";
        }
    }

    $mailbox->disconnect();
