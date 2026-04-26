<?php

namespace plugins\PageBuilder\Addons\HireTheBest;

use plugins\FormBuilder\SanitizeInput;
use plugins\PageBuilder\Fields\IconPicker;
use plugins\PageBuilder\Fields\Image;
use plugins\PageBuilder\Fields\Slider;
use plugins\PageBuilder\Fields\Summernote;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\PageBuilderBase;
use plugins\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use plugins\PageBuilder\Fields\ColorPicker;
use plugins\PageBuilder\Fields\Repeater;
use plugins\PageBuilder\Helpers\RepeaterField;

class HireTheBest extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'HireTheBest/HireTheBest.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        // Title field
        $output .= Text::get([
            'name' => 'title',
            'label' => __('Main Title'),
            'value' => $widget_saved_values['title'] ?? null,
            'placeholder' => __('Enter main title')
        ]);

        // Repeater for feature cards
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'feature_cards',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'icon',
                    'label' => __('Icon Image')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'card_title',
                    'label' => __('Card Title')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'card_description',
                    'label' => __('Card Description'),
                ],
            ]
        ]);

        // Right side image
        $output .= Image::get([
            'name' => 'right_image',
            'label' => __('Right Side Image'),
            'value' => $widget_saved_values['right_image'] ?? null,
        ]);

        // Background color
        $output .= ColorPicker::get([
            'name' => 'background_color',
            'label' => __('Background Color'),
            'value' => $widget_saved_values['background_color'] ?? '#F8F9FD',
        ]);

        // Padding
        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 40,
            'max' => 200,
        ]);
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 40,
            'max' => 200,
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();

        $title = SanitizeInput::esc_html($settings['title'] ?? 'All You Need to Hire The Best');
        $right_image = $settings['right_image'] ?? '';
        $background_color = $settings['background_color'] ?? '#F8F9FD';
        $padding_top = $settings['padding_top'] ?? 40;
        $padding_bottom = $settings['padding_bottom'] ?? 40;
        $feature_cards = $settings['feature_cards'] ?? [];

        return $this->renderBlade('HireTheBest.HireTheBest', compact(
            'title',
            'right_image',
            'background_color',
            'padding_top',
            'padding_bottom',
            'feature_cards'
        ));
    }

    public function addon_title()
    {
        return __('Hire The Best Section');
    }
}