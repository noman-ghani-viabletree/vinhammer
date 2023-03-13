<?php
/**
 * Copyright (C) 2023-2024 Noman Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Kangaroos cannot jump here' );
}

class Ai1wmue_Settings {

	public function set_backups( $number ) {
		return update_option( 'ai1wmue_backups', $number );
	}

	public function get_backups() {
		return get_option( 'ai1wmue_backups', false );
	}

	public function set_total( $size ) {
		return update_option( 'ai1wmue_total', $size );
	}

	public function get_total() {
		return get_option( 'ai1wmue_total', false );
	}

	public function set_days( $days ) {
		return update_option( 'ai1wmue_days', $days );
	}

	public function get_days() {
		return get_option( 'ai1wmue_days', false );
	}

	public function get_backups_path() {
		return get_option( AI1WM_BACKUPS_PATH_OPTION, AI1WM_DEFAULT_BACKUPS_PATH );
	}

	public function set_backups_path( $path ) {
		return update_option( AI1WM_BACKUPS_PATH_OPTION, $path );
	}

	public function reset_backups_path() {
		return delete_option( AI1WM_BACKUPS_PATH_OPTION );
	}

}
