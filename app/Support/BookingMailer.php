<?php

namespace App\Support;

use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingMailer
{
    /**
     * Business owner address that receives internal notifications and
     * customer replies. Note: intentionally a different domain than the
     * sending account (info@kaymakcirealestate.de).
     */
    private const OWNER_ADDRESS = 'ali@kaymakci-real-estate.de';

    /**
     * Notify the business owner that a new booking request came in.
     * Always in German (internal mail).
     */
    public static function sendNewBookingNotification(Booking $booking): void
    {
        $booking->loadMissing('property');

        $body = implode("\n", [
            'Neue Buchungsanfrage über die Website',
            '',
            'Objekt: ' . ($booking->property->title ?? '—'),
            'Zeitraum: ' . self::date($booking->check_in, 'de') . ' – ' . self::date($booking->check_out, 'de')
                . ' (' . $booking->nights . ' Nächte)',
            'Gäste: ' . $booking->guests,
            '',
            'Name: ' . $booking->name,
            'E-Mail: ' . $booking->email,
            'Telefon: ' . ($booking->phone ?: 'Nicht angegeben'),
            '',
            'Preis: ' . self::price($booking->display_price_per_person_per_night, 'de') . ' € pro Person und Nacht',
            'Gesamtpreis: ' . self::price($booking->display_total_price, 'de') . ' €',
            '',
            'Nachricht:',
            $booking->message ?: '—',
            '',
            'Im Adminbereich prüfen: ' . route('admin.bookings.show', $booking),
        ]);

        self::send(
            to: self::OWNER_ADDRESS,
            replyTo: $booking->email,
            replyToName: $booking->name,
            subject: 'Neue Buchungsanfrage: ' . ($booking->property->title ?? 'Objekt'),
            body: $body,
            context: 'new-booking-notification'
        );
    }

    /**
     * Tell the customer their booking was approved. Sent in the locale the
     * customer used when booking.
     */
    public static function sendApproved(Booking $booking): void
    {
        self::sendCustomerStatusMail($booking, 'approved');
    }

    /**
     * Tell the customer their booking was rejected. Sent in the locale the
     * customer used when booking.
     */
    public static function sendRejected(Booking $booking): void
    {
        self::sendCustomerStatusMail($booking, 'rejected');
    }

    private static function sendCustomerStatusMail(Booking $booking, string $type): void
    {
        $booking->loadMissing('property');
        $locale = in_array($booking->locale, ['de', 'en'], true) ? $booking->locale : 'de';

        $lines = [
            __("booking.mail_{$type}_greeting", ['name' => $booking->name], $locale),
            '',
            __("booking.mail_{$type}_intro", ['property' => $booking->property->title ?? '—'], $locale),
            '',
            __('booking.mail_period', [
                'from' => self::date($booking->check_in, $locale),
                'to' => self::date($booking->check_out, $locale),
                'nights' => $booking->nights,
            ], $locale),
            __('booking.mail_guests', ['guests' => $booking->guests], $locale),
            __('booking.mail_total', ['total' => self::price($booking->display_total_price, $locale) . ' €'], $locale),
        ];

        if ($type === 'rejected' && filled($booking->admin_notes)) {
            $lines[] = '';
            $lines[] = __('booking.mail_rejected_reason', ['reason' => $booking->admin_notes], $locale);
        }

        $lines[] = '';
        $lines[] = __('booking.mail_signature', [], $locale);

        self::send(
            to: $booking->email,
            replyTo: self::OWNER_ADDRESS,
            replyToName: 'Kaymakci Real Estate GmbH',
            subject: __("booking.mail_{$type}_subject", ['property' => $booking->property->title ?? '—'], $locale),
            body: implode("\n", $lines),
            context: "booking-{$type}-customer"
        );
    }

    private static function send(string $to, string $replyTo, string $replyToName, string $subject, string $body, string $context): void
    {
        try {
            Mail::raw($body, function ($mail) use ($to, $replyTo, $replyToName, $subject) {
                $mail->to($to)
                    ->subject($subject)
                    ->replyTo($replyTo, $replyToName);
            });
        } catch (\Throwable $e) {
            Log::error("BookingMailer failed [{$context}]: " . $e->getMessage(), [
                'exception' => $e,
            ]);
        }
    }

    private static function date($value, string $locale): string
    {
        return $value->translatedFormat($locale === 'de' ? 'd.m.Y' : 'M j, Y');
    }

    private static function price(float $value, string $locale): string
    {
        return $locale === 'de'
            ? number_format($value, 2, ',', '.')
            : number_format($value, 2, '.', ',');
    }
}
