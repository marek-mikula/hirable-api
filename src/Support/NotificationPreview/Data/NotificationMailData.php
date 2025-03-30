<?php

namespace Support\NotificationPreview\Data;

use App\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Spatie\LaravelData\Data;

class NotificationMailData extends Data
{
    public Mailable $mailable;

    public function getEnvelope(): Envelope
    {
        return once(fn (): Envelope => $this->mailable->envelope());
    }

    public function getSubject(): string
    {
        return once(fn (): string => $this->getEnvelope()->subject);
    }

    public function getTo(): string
    {
        return once(fn (): string => $this->formatAddresses($this->getEnvelope()->to));
    }

    public function getCc(): string
    {
        return once(fn (): string => $this->formatAddresses($this->getEnvelope()->cc));
    }

    public function getBcc(): string
    {
        return once(fn (): string => $this->formatAddresses($this->getEnvelope()->bcc));
    }

    public function getFrom(): string
    {
        return once(fn (): string => $this->formatAddresses($this->getEnvelope()->from));
    }

    public function getReplyTo(): string
    {
        return once(fn (): string => $this->formatAddresses($this->getEnvelope()->replyTo));
    }

    public function base64Html(): string
    {
        return once(fn (): string => base64_encode($this->mailable->render()));
    }

    private function formatAddresses(Address|array|null $addresses): string
    {
        if ($addresses === null) {
            $addresses = [];
        }

        if ($addresses instanceof Address) {
            $addresses = [$addresses];
        }

        $addresses = collect($addresses)->map(function (Address $address): string {
            $email = empty($address->address) ? 'EMPTY EMAIL' : $address->address;
            $name = empty($address->name) ? 'EMPTY NAME' : (string) $address->name;

            return "{$email}<{$name}>";
        });

        if ($addresses->isNotEmpty()) {
            return $addresses->implode(', ');
        }

        return 'NO ADDRESSES';
    }

    public static function create(Mailable $mailable): static
    {
        return static::from(['mailable' => $mailable]);
    }
}
