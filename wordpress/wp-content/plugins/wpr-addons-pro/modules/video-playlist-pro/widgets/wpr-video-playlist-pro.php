<?php
namespace WprAddonsPro\Modules\VideoPlaylistPro\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Video_Playlist_Pro extends \WprAddons\Modules\VideoPlaylist\Widgets\Wpr_Video_Playlist {

	public function add_control_playlist_query() {
		$this->add_control(
			'playlist_query',
			[
				'label' => esc_html__( 'Playlist Query', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom' => esc_html__( 'Custom URLs', 'wpr-addons' ),
					'playlist' => esc_html__( 'YouTube Playlist', 'wpr-addons' ),
				],
			]
		);
	}

	public function add_control_youtube_api_key() {
		$this->add_control(
			'youtube_api_key',
			[
				'label' => esc_html__( 'YouTube API Key', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => 'To get your <strong>Youtube API Key</strong> please watch this <strong><a href="https://youtu.be/LLAZUTbc97I" target="_blank">Video Tutorial</a></strong>.',
				'condition' => [
					'playlist_query' => 'playlist',
				],
			]
		);
	}

	public function add_control_youtube_playlist_id() {
		$this->add_control(
			'youtube_playlist_id',
			[
				'label' => esc_html__( 'YouTube Playlist ID', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => 'To get your <strong>Youtube Playlist ID</strong> go to YouTube Channel, select Playlist and find E.g: <strong>list=PLjFiZESrp9558M7Rghnk5s4sMq6m3RyOb</strong> in the URL and copy the ID.',
				'condition' => [
					'playlist_query' => 'playlist',
				],
			]
		);
	}

	public function add_repeater_args_video_urls() {
		return [
			'label' => esc_html__( 'Video URLs', 'wpr-addons' ),
			'type' => Controls_Manager::REPEATER,
			'default' => [
				[ 'video_url' => [ 'url' => 'https://youtu.be/OrtzJs-wzlw' ] ],
				[ 'video_url' => [ 'url' => 'https://youtu.be/zCfzzUuX8HE' ] ],
				[ 'video_url' => [ 'url' => 'https://youtu.be/Abw5LIIfgEo' ] ],
				[ 'video_url' => [ 'url' => 'https://youtu.be/dcpehUVAx0k' ] ],
				[ 'video_url' => [ 'url' => 'https://youtu.be/-wTaxzBxo6E' ] ],
				[ 'video_url' => [ 'url' => 'https://youtu.be/9qJH__RF--I' ] ],
			],
			'title_field' => '{{ video_url.url }}',
			'condition' => [
				'playlist_query' => 'custom',
			],
		];
	}

	public function add_repeater_args_custom_title() {
		return [
			'label' => esc_html__( 'Custom Title', 'wpr-addons' ),
			'type' => Controls_Manager::TEXT,
			'default' => '',
			'placeholder' => esc_html__( 'Leave empty to use default', 'wpr-addons' ),
			'label_block' => true,
		];
	}
}
