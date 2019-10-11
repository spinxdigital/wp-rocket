<?php
namespace WP_Rocket\Tests\Integration\Subscriber\CDN\RocketCDNSubscriber;

use PHPUnit\Framework\TestCase;
use WP_Rocket\Subscriber\CDN\RocketCDNSubscriber;
use WP_Rocket\Admin\Options;
use WP_Rocket\Admin\Options_Data;

class TestEnable extends TestCase {
    public function testWPRocketOptionsUpdated() {
        $request = new \WP_Rest_Request( 'PUT', '/wp-rocket/v1/rocketcdn/enable' );
        $request->set_body_params(
            [
                'url' => 'https://rocketcdn.me',
            ]
        );

        $options_api = new Options( 'wp_rocket_' );
        $options     = new Options_Data( $options_api->get( 'settings' ) );
        $rocketcdn   = new RocketCDNSubscriber( $options_api, $options );
        $rocketcdn->enable( $request );

        $wp_rocket_settings = get_option( 'wp_rocket_settings' );

        $this->assertSame(
            1,
            $wp_rocket_settings['cdn']
        );

        $this->assertContains(
            'https://rocketcdn.me',
            $wp_rocket_settings['cdn_cnames']
        );

        $this->assertContains(
            'all',
            $wp_rocket_settings['cdn_zone']
        );
    }
}