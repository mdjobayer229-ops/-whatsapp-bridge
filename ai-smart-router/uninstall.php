<?php
defined('WP_UNINSTALL_PLUGIN') or die;
delete_option('ai_router_settings');
delete_option('ai_router_exhausted_models');
delete_option('ai_router_logs');
delete_option('ai_router_stats');
delete_option('ai_router_current');
delete_option('ai_router_conversations');
delete_option('ai_router_skills');
$ts = wp_next_scheduled('ai_router_daily_reset');
if ($ts) wp_unschedule_event($ts, 'ai_router_daily_reset');
$ts2 = wp_next_scheduled('ai_router_skill_consolidate');
if ($ts2) wp_unschedule_event($ts2, 'ai_router_skill_consolidate');