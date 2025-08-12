<?php

namespace EnhancedImageModule;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

require_once ABSPATH . 'wp-content/themes/Divi/includes/builder-5/server/Framework/DependencyManagement/Interfaces/DependencyInterface.php';

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Framework\Utility\HTMLUtility;
use ET\Builder\FrontEnd\Module\Style;
use ET\Builder\Packages\Module\Module;
use ET\Builder\Packages\Module\Options\Element\ElementClassnames;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;

/**
 * Enhanced Image Module — Server Runtime (Divi 5)
 */
class EnhancedImageModule implements DependencyInterface {
    public function load() {
        add_action( 'init', [ self::class, 'register_module' ] );
    }

    public static function register_module() {
        $module_json_folder_path = dirname( __DIR__, 1 ) . '/visual-builder/src';

        ModuleRegistration::register_module(
            $module_json_folder_path,
            [ 'render_callback' => [ self::class, 'render_callback' ] ]
        );
    }

    public static function module_styles( $args ) {
        $elements = $args['elements'];

        Style::add([
            'id'            => $args['id'],
            'name'          => $args['name'],
            'orderIndex'    => $args['orderIndex'],
            'storeInstance' => $args['storeInstance'],
            'styles'        => [
                $elements->style([
                    'attrName'   => 'module',
                    'styleProps' => [
                        'disabledOn' => [
                            'disabledModuleVisibility' => $args['settings']['disabledModuleVisibility'] ?? null,
                        ],
                    ],
                ]),
                $elements->style([ 'attrName' => 'image' ]),
                $elements->style([ 'attrName' => 'caption' ]),
                $elements->style([ 'attrName' => 'description' ]),
            ],
        ]);
    }

    public static function module_script_data( $args ) {
        $args['elements']->script_data([ 'attrName' => 'module' ]);
    }

    public static function module_classnames( $args ) {
        $args['classnamesInstance']->add(
            ElementClassnames::classnames([
                'attrs' => $args['attrs']['module']['decoration'] ?? [],
            ])
        );
    }

    public static function render_callback( $attrs, $content, $block, $elements ) {
        $image_attrs = $attrs['image'] ?? [];
        $inner       = $image_attrs['innerContent']['desktop']['value'] ?? [];
        $src         = $inner['src'] ?? '';
        $alt         = $inner['alt'] ?? '';
        $link_url    = $inner['linkUrl'] ?? '';
        $link_target = $inner['linkTarget'] ?? 'off';
        $lightbox    = $image_attrs['advanced']['lightbox']['desktop']['value'] ?? 'off';

        $show_caption     = ( $attrs['module']['advanced']['showCaption']['desktop']['value'] ?? 'off' ) === 'on';
        $show_description = ( $attrs['module']['advanced']['showDescription']['desktop']['value'] ?? 'off' ) === 'on';

        $image_id = 0;
        if ( ! empty( $src ) ) {
            $image_id = attachment_url_to_postid( $src );
        }

        $media_caption     = '';
        $media_description = '';
        if ( $image_id ) {
            $media_caption = wp_get_attachment_caption( $image_id );
            $post = get_post( $image_id );
            $media_description = $post ? $post->post_content : '';
        }

        $final_caption = $show_caption ? $media_caption : '';
        $final_description = $show_description ? $media_description : '';

        // Placeholder SVG inline
        $placeholder_svg = '<svg width="64" height="64" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" style="opacity:.3;display:block;margin:auto">'
            . '<path fill="#f3f3f3" d="M 8 0 L 56 0 C 60.418278 0 64 3.581722 64 8 L 64 56 C 64 60.418278 60.418278 64 56 64 L 8 64 C 3.581722 64 0 60.418278 0 56 L 0 8 C 0 3.581722 3.581722 0 8 0 Z"/>'
            . '<path fill="#a3a3a3" d="M 8 44 L 20 28 L 32 44 L 44 20 L 56 44 L 8 44 Z"/>'
            . '<path fill="#6ec92f" fill-rule="evenodd" stroke="#6ec92f" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" d="M 60 12.953491 L 53.953491 12.953491 L 53.953491 19 L 51.046509 19 L 51.046509 12.953491 L 45 12.953491 L 45 10.046509 L 51.046509 10.046509 L 51.046509 4 L 53.953491 4 L 53.953491 10.046509 L 60 10.046509 L 60 12.953491 Z"/>'
            . '</svg>';

        if ( empty( $src ) ) {
            $image_html = '<div class="enhanced_image_module_placeholder">' . $placeholder_svg . '</div>';
        } else {
            $image_attributes = [];
            $sizes_attr = '';
            if ( $image_id ) {
                $srcset = wp_get_attachment_image_srcset( $image_id );
                $meta   = wp_get_attachment_metadata( $image_id );
                if ( $meta && isset( $meta['width'] ) ) {
                    $w = (int) $meta['width'];
                    $sizes_attr = sprintf('(max-width: %1$spx) 100vw, %1$spx', $w);
                }
                if ( $srcset ) { $image_attributes[] = 'srcset="' . esc_attr( $srcset ) . '"'; }
                if ( $sizes_attr )  { $image_attributes[] = 'sizes="' . esc_attr( $sizes_attr ) . '"'; }
                if ( $meta && isset( $meta['width'], $meta['height'] ) ) {
                    $image_attributes[] = 'width="' . (int) $meta['width'] . '"';
                    $image_attributes[] = 'height="' . (int) $meta['height'] . '"';
                }
            }
            $img_attrs = implode( ' ', $image_attributes );
            $img_tag = sprintf(
                '<img src="%s" alt="%s" class="enhanced_image_module_image" %s />',
                esc_url( $src ),
                esc_attr( $alt ),
                $img_attrs
            );

            if ( ! empty( $link_url ) && $lightbox !== 'on' ) {
                $target_attr = $link_target === 'on' ? ' target="_blank" rel="noopener"' : '';
                $image_html  = sprintf(
                    '<a href="%s"%s class="enhanced_image_module_link">%s</a>',
                    esc_url( $link_url ),
                    $target_attr,
                    $img_tag
                );
            } else {
                $image_html = $img_tag;
            }
        }

        $caption_html = '';
        if ( $show_caption && '' !== $final_caption ) {
            $caption_html = '<figcaption class="enhanced_image_module_caption">' . wp_kses_post( $final_caption ) . '</figcaption>';
        }

        $description_html = '';
        if ( $show_description && '' !== $final_description ) {
            $description_html = '<div class="enhanced_image_module_description">' . wp_kses_post( wpautop( $final_description ) ) . '</div>';
        }

        $style_components  = $elements->style_components([ 'attrName' => 'module' ]);
        $style_components .= $elements->style_components([ 'attrName' => 'image' ]);
        $style_components .= $elements->style_components([ 'attrName' => 'caption' ]);
        $style_components .= $elements->style_components([ 'attrName' => 'description' ]);

        $module_inner = HTMLUtility::render([
            'tag'               => 'figure',
            'attributes'        => [ 'class' => 'enhanced_image_module_inner' ],
            'childrenSanitizer' => 'et_core_esc_previously',
            'children'          => $image_html . $caption_html . $description_html,
        ]);

        $module_container_children = $style_components . $module_inner;

        return Module::render([
            'orderIndex'          => $block->parsed_block['orderIndex'],
            'storeInstance'       => $block->parsed_block['storeInstance'],
            'attrs'               => $attrs,
            'elements'            => $elements,
            'id'                  => $block->parsed_block['id'],
            'moduleClassName'     => 'enhanced_image_module',
            'name'                => $block->block_type->name,
            'classnamesFunction'  => [ self::class, 'module_classnames' ],
            'moduleCategory'      => $block->block_type->category,
            'stylesComponent'     => [ self::class, 'module_styles' ],
            'scriptDataComponent' => [ self::class, 'module_script_data' ],
            'children'            => $module_container_children,
        ]);
    }
}

add_action( 'divi_module_library_modules_dependency_tree', function( $dependency_tree ) {
    $dependency_tree->add_dependency( new EnhancedImageModule() );
} );