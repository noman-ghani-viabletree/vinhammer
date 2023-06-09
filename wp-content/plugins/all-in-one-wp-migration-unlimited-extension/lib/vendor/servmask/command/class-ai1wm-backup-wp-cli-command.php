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

if ( defined( 'WP_CLI' ) && ! class_exists( 'Ai1wm_Backup_WP_CLI_Command' ) ) {
	class Ai1wm_Backup_WP_CLI_Command extends WP_CLI_Command {
		public function __construct() {
			if ( ! defined( 'AI1WM_PLUGIN_NAME' ) ) {
				WP_CLI::error_multi_line(
					array(
						__( 'Extension requires All-in-One WP Migration plugin to be activated. ', AI1WM_PLUGIN_NAME ),
						__( 'You can get a copy of it here: https://wordpress.org/plugins/all-in-one-wp-migration/', AI1WM_PLUGIN_NAME ),
					)
				);
				exit;
			}

			if ( is_multisite() && ! defined( 'AI1WMME_PLUGIN_NAME' ) ) {
				WP_CLI::error_multi_line(
					array(
						__( 'WordPress Multisite is supported via our All-in-One WP Migration Multisite Extension.', AI1WM_PLUGIN_NAME ),
						__( 'You can get a copy of it here: https://servmask.com/products/multisite-extension', AI1WM_PLUGIN_NAME ),
					)
				);
				exit;
			}

			if ( ! is_dir( AI1WM_STORAGE_PATH ) ) {
				if ( ! mkdir( AI1WM_STORAGE_PATH ) ) {
					WP_CLI::error_multi_line(
						array(
							sprintf( __( 'All-in-One WP Migration is not able to create <strong>%s</strong> folder.', AI1WM_PLUGIN_NAME ), AI1WM_STORAGE_PATH ),
							__( 'You will need to create this folder and grant it read/write/execute permissions (0777) for the All-in-One WP Migration plugin to function properly.', AI1WM_PLUGIN_NAME ),
						)
					);
					exit;
				}
			}

			if ( ! is_dir( AI1WM_BACKUPS_PATH ) ) {
				if ( ! mkdir( AI1WM_BACKUPS_PATH ) ) {
					WP_CLI::error_multi_line(
						array(
							sprintf( __( 'All-in-One WP Migration is not able to create <strong>%s</strong> folder.', AI1WM_PLUGIN_NAME ), AI1WM_BACKUPS_PATH ),
							__( 'You will need to create this folder and grant it read/write/execute permissions (0777) for the All-in-One WP Migration plugin to function properly.', AI1WM_PLUGIN_NAME ),
						)
					);
					exit;
				}
			}
		}

		/**
		 * Creates a new backup.
		 *
		 * ## OPTIONS
		 *
		 * [--sites]
		 * : Export sites by id (To list sites use: wp site list --fields=blog_id,url)
		 *
		 * [--password]
		 * : Encrypt backup with password
		 *
		 * [--exclude-spam-comments]
		 * : Do not export spam comments
		 *
		 * [--exclude-post-revisions]
		 * : Do not export post revisions
		 *
		 * [--exclude-media]
		 * : Do not export media library (files)
		 *
		 * [--exclude-themes]
		 * : Do not export themes (files)
		 *
		 * [--exclude-inactive-themes]
		 * : Do not export inactive themes (files)
		 *
		 * [--exclude-muplugins]
		 * : Do not export must-use plugins (files)
		 *
		 * [--exclude-plugins]
		 * : Do not export plugins (files)
		 *
		 * [--exclude-inactive-plugins]
		 * : Do not export inactive plugins (files)
		 *
		 * [--exclude-cache]
		 * : Do not export cache (files)
		 *
		 * [--exclude-database]
		 * : Do not export database (sql)
		 *
		 * [--exclude-tables]
		 * : Do not export selected database tables (sql)
		 *
		 * [--exclude-email-replace]
		 * : Do not replace email domain (sql)
		 *
		 * [--replace]
		 * : Find and replace text in the database
		 *
		 * [<find>...]
		 * : A string to find for within the database
		 *
		 * [<replace>...]
		 * : Replace instances of the first string with this new string
		 *
		 * ## EXAMPLES
		 *
		 * $ wp ai1wm backup --replace "wp" "WordPress"
		 * Backup in progress...
		 * Backup complete.
		 * Backup file: migration-wp-20170913-095743-931.wpress
		 * Backup location: /repos/migration/wp/wp-content/ai1wm-backups/migration-wp-20170913-095743-931.wpress
		 *
		 * @subcommand backup
		 */
		public function backup( $args = array(), $assoc_args = array() ) {
			$params = array(
				'cli_args'   => $assoc_args,
				'secret_key' => get_option( AI1WM_SECRET_KEY, false ),
			);

			if ( function_exists( 'ai1wm_can_encrypt' ) && ai1wm_can_encrypt() && ! empty( $assoc_args['password'] ) ) {
				$params['options']['encrypt_backups']  = true;
				$params['options']['encrypt_password'] = $assoc_args['password'];
			}

			if ( isset( $assoc_args['exclude-spam-comments'] ) ) {
				$params['options']['no_spam_comments'] = true;
			}

			if ( isset( $assoc_args['exclude-post-revisions'] ) ) {
				$params['options']['no_post_revisions'] = true;
			}

			if ( isset( $assoc_args['exclude-media'] ) ) {
				$params['options']['no_media'] = true;
			}

			if ( isset( $assoc_args['exclude-themes'] ) ) {
				$params['options']['no_themes'] = true;
			}

			if ( isset( $assoc_args['exclude-inactive-themes'] ) ) {
				$params['options']['no_inactive_themes'] = true;
			}

			if ( isset( $assoc_args['exclude-muplugins'] ) ) {
				$params['options']['no_muplugins'] = true;
			}

			if ( isset( $assoc_args['exclude-plugins'] ) ) {
				$params['options']['no_plugins'] = true;
			}

			if ( isset( $assoc_args['exclude-inactive-plugins'] ) ) {
				$params['options']['no_inactive_plugins'] = true;
			}

			if ( isset( $assoc_args['exclude-cache'] ) ) {
				$params['options']['no_cache'] = true;
			}

			if ( isset( $assoc_args['exclude-database'] ) ) {
				$params['options']['no_database'] = true;
			} elseif ( isset( $assoc_args['exclude-tables'] ) ) {
				global $wpdb;

				if ( empty( $wpdb->use_mysqli ) ) {
					$mysql = new Ai1wm_Database_Mysql( $wpdb );
				} else {
					$mysql = new Ai1wm_Database_Mysqli( $wpdb );
				}

				// Include table prefixes
				if ( ai1wm_table_prefix() ) {
					$mysql->add_table_prefix_filter( ai1wm_table_prefix() );

					// Include table prefixes (Webba Booking)
					foreach ( array( 'wbk_services', 'wbk_days_on_off', 'wbk_locked_time_slots', 'wbk_appointments', 'wbk_cancelled_appointments', 'wbk_email_templates', 'wbk_service_categories', 'wbk_gg_calendars', 'wbk_coupons' ) as $table_name ) {
						$mysql->add_table_prefix_filter( $table_name );
					}
				}

				$tables = new cli\Table;

				$tables->setHeaders(
					array(
						'name' => sprintf( 'Tables (%s)', DB_NAME ),
					)
				);

				foreach ( $all_tables = $mysql->get_tables() as $table_name ) {
					$tables->addRow(
						array(
							'name' => $table_name,
						)
					);
				}

				$tables->display();
				$excluded_tables = array();

				while ( $table = trim( readline( 'Enter table name to exclude from backup (q=quit, empty=continue): ' ) ) ) {
					switch ( $table ) {
						case 'q':
							exit;

						default:
							if ( ! in_array( $table, $all_tables ) ) {
								WP_CLI::warning( __( 'Unknown table: ', AI1WM_PLUGIN_NAME ) . $table );
								break;
							}
							$excluded_tables[] = $table;
					}
				}

				if ( ! empty( $excluded_tables ) ) {
					$params['options']['exclude_db_tables'] = true;
					$params['excluded_db_tables']           = implode( ',', $excluded_tables );
				}
			}

			if ( isset( $assoc_args['exclude-email-replace'] ) ) {
				$params['options']['no_email_replace'] = true;
			}

			if ( isset( $assoc_args['replace'] ) ) {
				for ( $i = 0; $i < count( $args ); $i += 2 ) {
					if ( isset( $args[ $i ] ) && isset( $args[ $i + 1 ] ) ) {
						$params['options']['replace']['old_value'][] = $args[ $i ];
						$params['options']['replace']['new_value'][] = $args[ $i + 1 ];
					}
				}
			}

			if ( is_multisite() && isset( $assoc_args['sites'] ) ) {
				while ( ( $site_id = readline( 'Enter site ID (q=quit, l=list sites): ' ) ) ) {
					switch ( $site_id ) {
						case 'q':
							exit;

						case 'l':
							WP_CLI::runcommand( 'site list --fields=blog_id,url' );
							break;

						default:
							if ( ! get_blog_details( $site_id ) ) {
								WP_CLI::error_multi_line(
									array(
										__( 'A site with this ID does not exist.', AI1WM_PLUGIN_NAME ),
										__( 'To list the sites type `l`.', AI1WM_PLUGIN_NAME ),
									)
								);
								break;
							}

							$params['options']['sites'][] = $site_id;
					}
				}
			}

			WP_CLI::log( 'Backup in progress...' );

			try {

				// Disable completed timeout
				add_filter( 'ai1wm_completed_timeout', '__return_zero' );

				// Run export filters
				$params = Ai1wm_Export_Controller::export( $params );

			} catch ( Exception $e ) {
				WP_CLI::error( $e->getMessage() );
			}

			WP_CLI::success( __( 'Backup complete.', AI1WM_PLUGIN_NAME ) );
			WP_CLI::log( sprintf( __( 'Backup file: %s', AI1WM_PLUGIN_NAME ), ai1wm_archive_name( $params ) ) );
			WP_CLI::log( sprintf( __( 'Backup location: %s', AI1WM_PLUGIN_NAME ), ai1wm_backup_path( $params ) ) );
		}

		/**
		 * Get a list of backup files.
		 *
		 * ## EXAMPLES
		 *
		 * $ wp ai1wm list-backups
		 * +------------------------------------------------+--------------+-----------+
		 * | Backup name                                    | Date created | Size      |
		 * +------------------------------------------------+--------------+-----------+
		 * | migration-wp-20170908-152313-435.wpress        | 4 days ago   | 536.77 MB |
		 * | migration-wp-20170908-152103-603.wpress        | 4 days ago   | 536.77 MB |
		 * | migration-wp-20170908-152036-162.wpress        | 4 days ago   | 536.77 MB |
		 * | migration-wp-20170908-151428-266.wpress        | 4 days ago   | 536.77 MB |
		 * +------------------------------------------------+--------------+-----------+
		 *
		 * @subcommand list-backups
		 */
		public function list_backups( array $args, array $assoc_args ) {
			$backups = new cli\Table;

			$backups->setHeaders(
				array(
					'name' => __( 'Backup name', AI1WM_PLUGIN_NAME ),
					'date' => __( 'Date created', AI1WM_PLUGIN_NAME ),
					'size' => __( 'Size', AI1WM_PLUGIN_NAME ),
				)
			);

			$model = new Ai1wm_Backups;
			foreach ( $model->get_files() as $backup ) {
				$backups->addRow(
					array(
						'name' => $backup['filename'],
						'date' => sprintf( __( '%s ago', AI1WM_PLUGIN_NAME ), human_time_diff( $backup['mtime'] ) ),
						'size' => ai1wm_size_format( $backup['size'], 2 ),
					)
				);
			}

			$backups->display();
		}

		/**
		 * Browse backup files.
		 *
		 * ## OPTIONS
		 *
		 * <backup-file>
		 * : The path for backup file
		 *
		 * ## EXAMPLES
		 *
		 * $ wp ai1wm browse-backup wp-servmask.test-20220316-090649-a7baqq.wpress
		 * +------------------------------------------------+--------------+-----------+
		 * | File name                                      | Date created | Size      |
		 * +------------------------------------------------+--------------+-----------+
		 * | package.json                                   | 1 week ago   | 854.00 B  |
		 * | index.php                                      | 10 years ago | 28.00 B   |
		 * | plugins/akismet/LICENSE.txt                    | 7 years ago  | 17.67 KB  |
		 * | plugins/akismet/views/predefined.php           | 2 years ago  | 318.00 B  |
		 * +------------------------------------------------+--------------+-----------+
		 *
		 * @subcommand browse-backup
		 */
		public function browse_backup( $args = array(), $assoc_args = array() ) {
			$params = array(
				'cli_args'             => $assoc_args,
				'secret_key'           => get_option( AI1WM_SECRET_KEY, false ),
				'ai1wm_manual_restore' => true,
			);

			if ( ! isset( $args[0] ) ) {
				WP_CLI::error_multi_line(
					array(
						__( 'A backup name must be provided in order to proceed with the list backup files process.', AI1WM_PLUGIN_NAME ),
						__( 'Example: wp ai1wm backup_files migration-wp-20170913-095743-931.wpress', AI1WM_PLUGIN_NAME ),
					)
				);
				exit;
			}

			if ( ! is_file( ai1wm_backup_path( array( 'archive' => $args[0] ) ) ) ) {
				WP_CLI::error_multi_line(
					array(
						__( 'The backup file could not be located in wp-content/ai1wm-backups folder.', AI1WM_PLUGIN_NAME ),
						__( 'To list available backups use: wp ai1wm list-backups', AI1WM_PLUGIN_NAME ),
					)
				);
				exit;
			}

			if ( ! isset( $params['archive'] ) ) {
				$params['archive'] = $args[0];
			}

			if ( ! empty( $assoc_args['storage'] ) ) {
				$params['storage'] = $assoc_args['storage'];
			}

			try {
				// Disable completed timeout
				add_filter( 'ai1wm_completed_timeout', '__return_zero' );

				$table = new cli\Table;

				$table->setHeaders(
					array(
						'name' => __( 'File name', AI1WM_PLUGIN_NAME ),
						'date' => __( 'Date created', AI1WM_PLUGIN_NAME ),
						'size' => __( 'Size', AI1WM_PLUGIN_NAME ),
					)
				);

				$archive = new Ai1wm_Extractor( ai1wm_archive_path( $params ) );
				$files   = $archive->list_files();

				foreach ( $files as $file ) {
					$table->addRow(
						array(
							'name' => $file['filename'],
							'date' => sprintf( __( '%s ago', AI1WM_PLUGIN_NAME ), human_time_diff( $file['mtime'] ) ),
							'size' => ai1wm_size_format( $file['size'], 2 ),
						)
					);
				}

				$table->display();
			} catch ( Exception $e ) {
				WP_CLI::error( $e->getMessage() );
			}
		}

		/**
		 * Extract backup to provided directory.
		 *
		 * ## OPTIONS
		 *
		 * <backup-file>
		 * : The path for backup file
		 *
		 * [--extract-path]
		 * : Extract backup files to provided path (wp ai1wm extract-backup wp-servmask.test-20220316-090649-a7baqq.wpress --extract-path=/tmp)
		 *
		 * ## EXAMPLES
		 *
		 * $ wp ai1wm extract-backup wp-servmask.test-20220316-090649-a7baqq.wpress --extract-path=/tmp
		 *
		 * @subcommand extract-backup
		 */
		public function extract_backup( $args = array(), $assoc_args = array() ) {
			$params = array(
				'cli_args'             => $assoc_args,
				'secret_key'           => get_option( AI1WM_SECRET_KEY, false ),
				'ai1wm_manual_restore' => true,
			);

			if ( ! isset( $args[0] ) ) {
				WP_CLI::error_multi_line(
					array(
						__( 'A backup name must be provided in order to proceed with the extract process.', AI1WM_PLUGIN_NAME ),
						__( 'Example: wp ai1wm backup-extract migration-wp-20170913-095743-931.wpress', AI1WM_PLUGIN_NAME ),
					)
				);
				exit;
			}

			if ( ! isset( $assoc_args['extract-path'] ) ) {
				WP_CLI::error_multi_line(
					array(
						__( 'A backup extract path must be provided and writable in order to proceed with the extract process.', AI1WM_PLUGIN_NAME ),
						__( 'Example: wp ai1wm backup-extract migration-wp-20170913-095743-931.wpress --extract-path=/tmp', AI1WM_PLUGIN_NAME ),
					)
				);
				exit;
			}

			if ( ! is_file( ai1wm_backup_path( array( 'archive' => $args[0] ) ) ) ) {
				WP_CLI::error_multi_line(
					array(
						__( 'The backup file could not be located in wp-content/ai1wm-backups folder.', AI1WM_PLUGIN_NAME ),
						__( 'To list available backups use: wp ai1wm list-backups', AI1WM_PLUGIN_NAME ),
					)
				);
				exit;
			}

			$params['extract_path'] = $assoc_args['extract-path'];

			if ( ! is_writable( $params['extract_path'] ) ) {
				WP_CLI::error_multi_line(
					array(
						__( 'The extract folder is not writable. Check that directory exists and writable.', AI1WM_PLUGIN_NAME ),
					)
				);
				exit;
			}

			if ( ! isset( $params['archive'] ) ) {
				$params['archive'] = $args[0];
			}

			if ( ! empty( $assoc_args['storage'] ) ) {
				$params['storage'] = $assoc_args['storage'];
			}

			try {
				// Disable completed timeout
				add_filter( 'ai1wm_completed_timeout', '__return_zero' );

				$archive = new Ai1wm_Extractor( ai1wm_archive_path( $params ) );

				while ( $archive->has_not_reached_eof() ) {
					$archive->extract_one_file_to( $params['extract_path'] );
				}
			} catch ( Exception $e ) {
				WP_CLI::error( sprintf( __( 'Unable to extract files: %s', AI1WM_PLUGIN_NAME ), $e->getMessage() ) );
				exit;
			}
		}

		/**
		 * Restores a backup.
		 *
		 * ## OPTIONS
		 *
		 * <file>
		 * : Name of the backup file
		 *
		 * [--yes]
		 * : Automatically confirm the restore operation
		 *
		 * ## EXAMPLES
		 *
		 * $ wp ai1wm restore migration-wp-20170913-095743-931.wpress
		 * Restore in progress...
		 * Restore complete.
		 *
		 * @subcommand restore
		 */
		public function restore( $args = array(), $assoc_args = array() ) {
			$params = array(
				'cli_args'   => $assoc_args,
				'secret_key' => get_option( AI1WM_SECRET_KEY, false ),
			);

			if ( ! isset( $args[0] ) ) {
				WP_CLI::error_multi_line(
					array(
						__( 'A backup name must be provided in order to proceed with the restore process.', AI1WM_PLUGIN_NAME ),
						__( 'Example: wp ai1wm restore migration-wp-20170913-095743-931.wpress', AI1WM_PLUGIN_NAME ),
					)
				);
				exit;
			}

			if ( ! is_file( ai1wm_backup_path( array( 'archive' => $args[0] ) ) ) ) {
				WP_CLI::error_multi_line(
					array(
						__( 'The backup file could not be located in wp-content/ai1wm-backups folder.', AI1WM_PLUGIN_NAME ),
						__( 'To list available backups use: wp ai1wm list-backups', AI1WM_PLUGIN_NAME ),
					)
				);
				exit;
			}

			if ( ! isset( $params['archive'] ) ) {
				$params['archive'] = $args[0];
			}

			if ( ! isset( $params['storage'] ) ) {
				$params['storage'] = ai1wm_storage_folder();
			}

			if ( ! isset( $params['ai1wm_manual_restore'] ) ) {
				$params['ai1wm_manual_restore'] = 1;
			}

			WP_CLI::log( __( 'Restore in progress...', AI1WM_PLUGIN_NAME ) );

			try {
				// Disable completed timeout
				add_filter( 'ai1wm_completed_timeout', '__return_zero' );

				// Run import filters
				$params = Ai1wm_Import_Controller::import( $params );

			} catch ( Exception $e ) {
				WP_CLI::error( $e->getMessage() );
			}

			WP_CLI::success( __( 'Restore complete.', AI1WM_PLUGIN_NAME ) );
		}
	}
}
