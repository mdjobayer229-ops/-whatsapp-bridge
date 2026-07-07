=== AI Smart Router ===
Contributors: ai-router
Tags: ai, chat, openrouter, whatsapp, smart-router
Requires at least: 5.5
Tested up to: 6.4
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later

24/7 AI assistant with 25 free models, 5 API keys, auto-failover, and WhatsApp integration.

== Description ==

AI Smart Router gives you 24/7 AI chat on your WordPress site using 25 free OpenRouter models across 5 API keys.

= Features =

* 25 free AI models ranked by intelligence (Tencent Hy3 295B #1)
* 5 API key slots with automatic failover
* Smart routing: 429/500/503 → auto-switch to next model
* All keys exhausted → reset daily via cron
* REST API endpoint for external services
* [ai_chat] shortcode — WhatsApp-style web chat UI
* WhatsApp Baileys bridge compatible
* Settings page in WordPress admin

== Installation ==

1. Upload the i-smart-router folder to /wp-content/plugins/
2. Activate through the 'Plugins' menu in WordPress
3. Go to Settings → AI Smart Router
4. Add your OpenRouter API keys and start chatting

== Shortcode ==

[ai_chat]

Place in any page or post for a full WhatsApp-style chat interface.

== REST API ==

POST /wp-json/ai-router/v1/chat
Body: {"message": "your question"}

== Changelog ==

= 1.0.0 =
Initial release.
