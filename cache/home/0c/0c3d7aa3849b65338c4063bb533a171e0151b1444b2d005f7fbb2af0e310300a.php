<?php

/* index.twig */
class __TwigTemplate_cce6a2686e303fc3d46ea0c7bb249a9c6fa3c0648106708e3a6f7b27756502f1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo twig_escape_filter($this->env, dumper(($context["data"] ?? null)), "html", null, true);
    }

    public function getTemplateName()
    {
        return "index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{{data|dumper}}", "index.twig", "D:\\phpStudy\\WWW\\framework\\BrickPHP\\app\\View\\home\\index.twig");
    }
}
