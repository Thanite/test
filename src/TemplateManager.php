<?php

class TemplateManager
{
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        $matches = [];
        if (preg_match_all('/\[(\w*):(\w*)\]/', $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $replacement = $this->replaceText($match[1], $match[2], $data);
                if (!$replacement) {
                    continue;
                }
                $text = str_replace(
                    '[' . $match[1] . ':' . $match[2] .']',
                    $replacement,
                    $text
                );
            }
        }
        return $text;
    }

    private function replaceText($class, $function, $data)
    {
        $context = $this->getContext($data);
        if (isset($context[$class]) and $context[$class]) {
            $object = $context[$class];
        } else {
            return false;
        }
        if (is_callable(array($object, $function), false, $callable_name)) {
            return $object->$function();
        } else {
            throw new \Exception('Fonction ' . $function . ' introuvable dans la classe ' . $object);
        }

        return false;
    }

    private function getContext($data)
    {
        $APPLICATION_CONTEXT = ApplicationContext::getInstance();

        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : false;
        $user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();

        return [
            'quote' => $quote,
            'user' => $user
        ];
    }
}
