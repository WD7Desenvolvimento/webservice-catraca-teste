<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initView()
    {
        // Initialize view
        if (null !== $this->getPluginResource('view')) {
            $view = $this->getPluginResource('view')->getView();
        } else {
            $view = new Zend_View();
        }
        $view->docType('HTML5');
        $view->headMeta()
            ->appendHttpEquiv('Content-Type', 'text/html; Charset=UTF-8')
            ->appendHttpEquiv('X-UA-Compatible', 'IE=Edge')
            ->appendName('viewport', 'width=device-width, initial-scale=1')
            ->appendName('Description', 'This is an in2it training demo application')
            ->appendName('Keywords', 'in2it, training, demo, zend framework, zf, zf1');
        $view->headTitle('Demo Application');
        $view->headTitle()->setSeparator(' | ');
        $view->headLink()
            ->headLink(array (
                'rel' => 'icon',
                'href' => $view->baseUrl('/media/favicon.ico')
            ))
            ->appendStylesheet($view->baseUrl('/css/bootstrap.min.css'))
            ->appendStylesheet($view->baseUrl('/css/bootstrap-theme.min.css'))
            ->appendStylesheet($view->baseUrl('/css/style.css'));
        $view->inlineScript()
            ->appendFile($view->baseUrl('/js/jquery-1.11.0.min.js'))
            ->appendFile($view->baseUrl('/js/bootstrap.min.js'));

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);

        // Return it, so that it can be stored by the bootstrap
        return $view;
    }
}

