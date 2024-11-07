<?php

namespace dash\lib;

class template
{
    private $_templateParts;
    private $_action_view;
    private $_data;

    public function __construct(array $templateParts)
    {
        $this->_templateParts = $templateParts;
    }

    public function setActionViewFile($action_view)
    {
        $this->_action_view = $action_view;
    }

    public function setAppData($data)
    {
        $this->_data = $data;
    }

    public function renderTemplateHeaderStart()
    {
        require_once  TEMPLATE_PATH . 'templateHeaderStart.php';
    }

    public function renderTemplateHeaderEnd()
    {
        require_once  TEMPLATE_PATH . 'templateHeaderEnd.php';
    }

    public function renderTemplateFooter()
    {
        require_once  TEMPLATE_PATH . 'footer.php';
    }

    public function renderTemplateEnd()
    {
        require_once  TEMPLATE_PATH . 'templateEnd.php';
    }

    public function renderTemplateBlocks()
    {
        if (!array_key_exists('template', $this->_templateParts)) {
            trigger_error('sorry you have to define the template blocks.', E_USER_WARNING);
        } else {
            $parts = $this->_templateParts['template'];
            if (!empty($parts)) {
                foreach ($parts as $partKey => $file) {
                    if ($partKey === ':view') {
                        require_once $this->_action_view;
                    } else {
                        require_once $file;
                    }
                }
            }
        }
    }


    private function renderHeaderResources()
    {
        $output = '';
        if (!array_key_exists('header_resources', $this->_templateParts)) {
            trigger_error('sorry you have to define the header_resources.', E_USER_WARNING);
        } else {
            $resources = $this->_templateParts['header_resources'];
            //Generate CSS links
            $css = $resources['css'];
            if (!empty($css)) {
                foreach ($css as $cssKey => $path) {
                    $output .= '<link type="text/css" rel="stylesheet" href="' . $path . '"/>';
                }
            }
            //Generate JS links
            // $js = $resources['js'];
            // foreach ($js as $jsKey => $path) {
            //     if ($jsKey === ':view') {
            //         $output .= '<script src="' . $path . '">';
            //     }
            // }
        }
        echo $output;
    }

    private function renderFooterResources()
    {
        $output = '';
        if (!array_key_exists('footer_resources', $this->_templateParts)) {
            trigger_error('sorry you have to define the header_resources.', E_USER_WARNING);
        } else {
            $resources = $this->_templateParts['footer_resources'];
            //Generate CSS links
            // $css = $resources['css'];
            // if (!empty($css)) {
            //     foreach ($css as $cssKey => $path) {
            //         if ($cssKey === ':view') {
            //             $output .= '<link type="text/css" rel="stylesheet" href="' . $path . '">';
            //         }
            //     }
            // }
            // Generate JS links
            $js = $resources['js'];
            foreach ($js as $jsKey => $path) {
                $output .= '<script src="' . $path . '"><script/>';
            }
        }
        echo $output;
    }


    public function renderApp()
    {
        extract($this->_data);
        $this->renderTemplateHeaderStart();
        $this->renderHeaderResources();
        $this->renderTemplateHeaderEnd();
        $this->renderTemplateBlocks();
        $this->renderTemplateFooter();
        $this->renderFooterResources();
        $this->renderTemplateEnd();
    }
}
