<?php

namespace plugins\PageBuilder\Addons\GetStarted;

use plugins\FormBuilder\SanitizeInput;
use plugins\PageBuilder\Fields\Image;
use plugins\PageBuilder\Fields\Slider;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\PageBuilderBase;
use plugins\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use plugins\PageBuilder\Fields\ColorPicker;

class GetStarted2 extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'get-started/get-started.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Title'),
            'value' => $widget_saved_values['title'] ?? null,
            'placeholder' => __('Enter title')
        ]);

        $output .= Text::get([
            'name' => 'subtitle',
            'label' => __('Subtitle'),
            'value' => $widget_saved_values['subtitle'] ?? null,
            'placeholder' => __('Enter subtitle')
        ]);

        $output .= Text::get([
            'name' => 'button_text',
            'label' => __('Button Text'),
            'value' => $widget_saved_values['button_text'] ?? null,
            'placeholder' => __('Join Free')
        ]);

        $output .= Text::get([
            'name' => 'button_link',
            'label' => __('Button Link'),
            'value' => $widget_saved_values['button_link'] ?? null,
            'placeholder' => __('Enter button URL')
        ]);

        // REMOVED: Background Image field
        // KEPT: Decorative Image field for the arc/svg
        $output .= Image::get([
            'name' => 'decorative_image',
            'label' => __('Decorative Image'),
            'value' => $widget_saved_values['decorative_image'] ?? null,
            'description' => __('Upload the decorative arc/svg image that appears in the bottom-left corner')
        ]);

        $output .= ColorPicker::get([
            'name' => 'gradient_from',
            'label' => __('Gradient From Color'),
            'value' => $widget_saved_values['gradient_from'] ?? null,
        ]);

        $output .= ColorPicker::get([
            'name' => 'gradient_to',
            'label' => __('Gradient To Color'),
            'value' => $widget_saved_values['gradient_to'] ?? null,
        ]);

        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 40,
            'max' => 500,
        ]);

        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 40,
            'max' => 500,
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $title = SanitizeInput::esc_html($settings['title'] ?? __('Get Started with Xilancer'));
        $subtitle = SanitizeInput::esc_html($settings['subtitle'] ?? __('Connect with top freelancers or showcase your skills to clients worldwide. Start your journey today and turn ideas into successful projects.'));
        $button_text = SanitizeInput::esc_html($settings['button_text'] ?? __('Join Free'));
        $button_link = SanitizeInput::esc_url($settings['button_link'] ?? '#');

        // REMOVED: background_image
        $decorative_image = $settings['decorative_image'] ?? '';
        $gradient_from = $settings['gradient_from'] ?? '';
        $gradient_to = $settings['gradient_to'] ?? '';
        $padding_top = $settings['padding_top'] ?? 40;
        $padding_bottom = $settings['padding_bottom'] ?? 40;

        return $this->renderBlade('GetStarted.GetStarted2', compact([
            'title',
            'subtitle',
            'button_text',
            'button_link',
            'decorative_image',
            'gradient_from',
            'gradient_to',
            'padding_top',
            'padding_bottom'
        ]));
    }

    public function addon_title()
    {
        return __('Get Started');
    }
}