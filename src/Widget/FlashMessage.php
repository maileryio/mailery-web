<?php

namespace Mailery\Web\Widget;

use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\Yii\Bootstrap5\Alert;
use Yiisoft\Widget\Widget;

final class FlashMessage extends Widget
{
    /**
     * @var type
     */
    private FlashInterface $flash;

    /**
     * @param FlashInterface $flash
     */
    public function __construct(FlashInterface $flash)
    {
        $this->flash = $flash;
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $flashes = $this->flash->getAll();

        $html = [];
        foreach ($flashes as $type => $data) {
            foreach ($data as $message) {
                $html[] = Alert::widget()
                    ->options([
                        'class' => "alert-{$type}",
                        'encode' => false,
                    ])
                    ->body($message['body'])
                    ->closeButtonEnabled(false)
                ;
            }
        }

        return implode($html);
    }
}