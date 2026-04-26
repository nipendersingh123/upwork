<?php

namespace plugins\PageBuilder\Addons\HowItWorks;

use plugins\FormBuilder\SanitizeInput;
use plugins\PageBuilder\Fields\ColorPicker;
use plugins\PageBuilder\Fields\Image;
use plugins\PageBuilder\Fields\Repeater;
use plugins\PageBuilder\Fields\Slider;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\Fields\Textarea;
use plugins\PageBuilder\Helpers\RepeaterField;
use plugins\PageBuilder\PageBuilderBase;
use plugins\PageBuilder\Traits\LanguageFallbackForPageBuilder;

class HowItWorks extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'HowItWorks/HowItWorks.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        // Change from Text field to Textarea field for section_title
        $output .= Textarea::get([
            'name' => 'section_title',
            'label' => __('Section Title'),
            'value' => $widget_saved_values['section_title'] ?? null,
            'placeholder' => __('How It Works'),
            'info' => __('Use line breaks (Enter key) to create multi-line titles')
        ]);

        // Repeater for Steps
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'steps',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'icon',
                    'label' => __('Step Icon/Image')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'title',
                    'label' => __('Step Title')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'description',
                    'label' => __('Step Description')
                ]
            ]
        ]);

        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 112,
            'max' => 500,
        ]);

        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 112,
            'max' => 500,
        ]);

        $output .= ColorPicker::get([
            'name' => 'section_bg',
            'label' => __('Background Color'),
            'value' => $widget_saved_values['section_bg'] ?? '#F8F9FD',
            'info' => __('select background color for the section'),
        ]);

        $output .= ColorPicker::get([
            'name' => 'card_bg',
            'label' => __('Card Background Color'),
            'value' => $widget_saved_values['card_bg'] ?? '#FFFFFF',
            'info' => __('select background color for cards'),
        ]);

        $output .= ColorPicker::get([
            'name' => 'icon_bg',
            'label' => __('Icon Background Color'),
            'value' => $widget_saved_values['icon_bg'] ?? '#E6F7F7',
            'info' => __('select background color for icon circles'),
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $title = SanitizeInput::esc_html($settings['section_title'] ?? 'How It Works');

        $padding_top = $settings['padding_top'] ?? 112;
        $padding_bottom = $settings['padding_bottom'] ?? 112;
        $section_bg = $settings['section_bg'] ?? '#F8F9FD';
        $card_bg = $settings['card_bg'] ?? '#FFFFFF';
        $icon_bg = $settings['icon_bg'] ?? '#E6F7F7';

        $steps_data = $settings['steps'] ?? '';

        return $this->renderBlade('HowItWorks.HowItWorks', compact([
            'title',
            'padding_top',
            'padding_bottom',
            'section_bg',
            'card_bg',
            'icon_bg',
            'steps_data'
        ]));
    }

    public function addon_title()
    {
        return __('Home: How It Works');
    }
}