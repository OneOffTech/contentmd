---
title: Privacy & Cookie Policy
description: How contentmd.org processes your personal data and uses cookies, in compliance with EU Regulation 2016/679 (GDPR).
card:
  template: '_og.page'
  path: /assets/og/privacy.png
---

@extends('_layouts.main')

@section('body')

<x-page-hero>
    Privacy &amp; Cookie Policy

    <x-slot name="description">
        How we processes your personal data and uses cookies, in compliance with EU Regulation 2016/679 (GDPR).
    </x-slot>
</x-page-hero>


<div class="pt-8">
    <x-container class="mt-4 mb-12">
        <div class="prose prose-zinc dark:prose-invert max-w-none">

            <h2>Data Controller</h2>
            <p><strong>Controller</strong>: Oneofftech-UG (haftungsbeschränkt) &mdash; <a href="mailto:info@oneofftech.de">info@oneofftech.de</a></p>
            <p><strong>Data Protection Officer</strong>: <a href="mailto:privacy@oneofftech.xyz">privacy@oneofftech.xyz</a></p>

            <h2>Data we collect</h2>

            <h3>Server logs</h3>
            <p>When you visit contentmd.org, our web server automatically records:</p>
            <ul>
                <li>IP address</li>
                <li>Request path, method, and HTTP status code</li>
                <li>Date and time of the request</li>
                <li>Browser and operating system (User-Agent header)</li>
                <li>Referring URL</li>
            </ul>
            <p>These logs are used solely to keep the website running and diagnose technical issues. They are retained for 6 months and then deleted.</p>
            <p>We use no analytics services, tracking pixels, session recording tools, or any other third-party monitoring.</p>

            <h3>Agent mode and session storage</h3>
            <p>The agent mode toggle saves your viewing preference (human or agent) in your browser&rsquo;s session storage. This data stays on your device and is never transmitted to our servers. No personal data is involved.</p>

            <h2>Cookie policy</h2>
            <p>We do not use analytics, advertising, or tracking cookies.</p>
            <p>To operate the Agent Mode toggle we store a <code>contentmd-mode</code> entry in session storage. These data do not identify you personally and expire when you close your browser.</p>

            <h2>Legal basis</h2>
            <p>Server log processing is based on our legitimate interest (Art. 6(1)(f) GDPR) in maintaining the security and reliable operation of this website.</p>

            <h2>Data recipients</h2>
            <p>We do not sell or share your personal data with third parties for marketing or any other purposes.</p>
            <p>Hosting: Hetzner Online GmbH, Germany &mdash; our infrastructure provider, appointed as data processor under Article 28 GDPR.</p>

            <h2>International transfers</h2>
            <p>We do not transfer personal data outside the European Union.</p>

            <h2>Data retention</h2>
            <p>Server logs are retained for 6 months, after which they are permanently deleted.</p>

            <h2>Your rights</h2>
            <p>Under the GDPR you have the right to access, rectify, or erase your personal data, to restrict or object to processing, and to lodge a complaint with a supervisory authority.</p>
            <p>To exercise any of these rights, contact our DPO at <a href="mailto:privacy@oneofftech.xyz">privacy@oneofftech.xyz</a>.</p>

            <h2>Data security</h2>
            <p>All data in transit is protected with TLS. Our servers are located and managed at data centres in Germany.</p>

            <h2>Changes to this policy</h2>
            <p>We may update this policy at any time. The current version is always available at this URL.</p>

        </div>
    </x-container>
</div>


<div class="h-16 agent:hidden"></div>

@endsection

@push('markdown')

## Data Controller

**Controller**: Oneofftech-UG (haftungsbeschränkt) — [info@oneofftech.de](mailto:info@oneofftech.de)

**Data Protection Officer**: [privacy@oneofftech.xyz](mailto:privacy@oneofftech.xyz)

## Data we collect

### Server logs

When you visit contentmd.org, our web server automatically records:

- IP address
- Request path, method, and HTTP status code
- Date and time of the request
- Browser and operating system (User-Agent header)
- Referring URL

These logs are used solely to keep the website running and diagnose technical issues. They are retained for **6 months** and then deleted.

We use no analytics services, tracking pixels, session recording tools, or any other third-party monitoring.

### Agent mode and local storage

The agent mode toggle saves your viewing preference (human or agent) in your browser's session storage. This data stays on your device and is never transmitted to our servers. No personal data is involved.

## Cookie policy

We do not use analytics, advertising, or tracking cookies.

The website may set technically necessary session cookies to operate core functionality. These cookies do not identify you personally and expire when you close your browser.

## Legal basis

Server log processing is based on our legitimate interest (Art. 6(1)(f) GDPR) in maintaining the security and reliable operation of this website.

## Data recipients

We do not sell or share your personal data with third parties for marketing or any other purposes.

**Hosting**: Hetzner Online GmbH, Germany — our infrastructure provider, appointed as data processor under Article 28 GDPR.

## International transfers

We do not transfer personal data outside the European Union.

## Data retention

Server logs are retained for **6 months**, after which they are permanently deleted.

## Your rights

Under the GDPR you have the right to access, rectify, or erase your personal data, to restrict or object to processing, and to lodge a complaint with a supervisory authority.

To exercise any of these rights, contact our DPO at [privacy@oneofftech.xyz](mailto:privacy@oneofftech.xyz).

## Data security

All data in transit is protected with TLS. Our servers are located and managed at data centres in Germany.

## Changes to this policy

We may update this policy at any time. The current version is always available at this URL.

@endpush
