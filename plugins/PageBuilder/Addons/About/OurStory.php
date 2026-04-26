<?php

namespace plugins\PageBuilder\Addons\About;

use plugins\FormBuilder\SanitizeInput;
use plugins\PageBuilder\Fields\Image;
use plugins\PageBuilder\Fields\Slider;
use plugins\PageBuilder\Fields\Summernote;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\PageBuilderBase;
use plugins\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use plugins\PageBuilder\Fields\ColorPicker;
use plugins\PageBuilder\Fields\Repeater;
use plugins\PageBuilder\Helpers\RepeaterField;

class OurStory extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'about/OurStory.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        // Main Repeater for Story/Vision/Values Sections
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'story_sections',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'section_title',
                    'label' => __('Section Title (e.g., Our Story, Our Vision)')
                ],
                [
                    'type' => RepeaterField::SUMMERNOTE,
                    'name' => 'section_content',
                    'label' => __('Section Subtitle/Description')
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'section_image',
                    'label' => __('Section Image'),
                ],
                [
                    'type' => RepeaterField::SELECT,
                    'name' => 'image_position',
                    'label' => __('Image Position'),
                    'options' => [
                        'right' => __('Right'),
                        'left' => __('Left')
                    ]
                ],

            ]
        ]);

        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 80,
            'max' => 500,
        ]);

        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 80,
            'max' => 500,
        ]);

        $output .= Slider::get([
            'name' => 'section_spacing',
            'label' => __('Section Spacing (between sections)'),
            'value' => $widget_saved_values['section_spacing'] ?? 128,
            'max' => 300,
        ]);

        $output .= ColorPicker::get([
            'name' => 'section_bg',
            'label' => __('Background Color'),
            'value' => $widget_saved_values['section_bg'] ?? '#F8F9FD',
            'info' => __('select color you want to show in frontend'),
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();

        $story_sections = $settings['story_sections'] ?? [];
        $padding_top = $settings['padding_top'] ?? 80;
        $padding_bottom = $settings['padding_bottom'] ?? 80;
        $section_spacing = $settings['section_spacing'] ?? 128;
        $section_bg = $settings['section_bg'] ?? '#F8F9FD';

        return $this->renderBlade('about.our-story', compact([
            'story_sections',
            'padding_top',
            'padding_bottom',
            'section_spacing',
            'section_bg'
        ]));
    }

    public function addon_title()
    {
        return __('About - Our Story/Vision/Values');
    }
}