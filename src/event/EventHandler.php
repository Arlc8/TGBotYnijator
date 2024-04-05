<?php

declare(strict_types=1);

namespace event;

use utils\Config;
use utils\Colors;
use utils\Logger;
use Revolt\EventLoop;
use danog\MadelineProto\ParseMode;
use danog\MadelineProto\MTProtoTools\DialogId;
use danog\MadelineProto\EventHandler\Attributes\Handler;
use danog\MadelineProto\SimpleEventHandler;


final class EventHandler extends SimpleEventHandler {
    public const BASSBOOSTED_WAVEFORM = [
        0 => 17,
        1 => 17,
        2 => 17,
        3 => 17,
        4 => 17,
        5 => 17,
        6 => 17,
        7 => 17,
        8 => 17,
        9 => 17,
        10 => 17,
        11 => 17,
        12 => 17,
        13 => 17,
        14 => 17,
        15 => 17,
        16 => 17,
        17 => 17,
        18 => 17,
        19 => 17,
        20 => 17,
        21 => 17,
        22 => 17,
        23 => 17,
        24 => 17,
        25 => 17,
        26 => 17,
        27 => 17,
        28 => 17,
        29 => 17,
        30 => 17,
        31 => 17,
        32 => 17,
        33 => 17,
        34 => 17,
        35 => 17,
        36 => 17,
        37 => 17,
        38 => 17,
        39 => 17,
        40 => 17,
        41 => 17,
        42 => 17,
        43 => 17,
        44 => 17,
        45 => 17,
        46 => 17,
        47 => 17,
        48 => 17,
        49 => 17,
        50 => 17,
        51 => 17,
        52 => 17,
        53 => 17,
        54 => 17,
        55 => 17,
        56 => 17,
        57 => 17,
        58 => 17,
        59 => 17,
        60 => 17,
        61 => 17,
        62 => 17,
        63 => 17,
        64 => 17,
        65 => 17,
        66 => 17,
        67 => 17,
        68 => 17,
        69 => 17,
        70 => 17,
        71 => 17,
        72 => 17,
        73 => 17,
        74 => 17,
        75 => 17,
        76 => 17,
        77 => 17,
        78 => 17,
        79 => 17,
        80 => 17,
        81 => 17,
        82 => 17,
        83 => 17,
        84 => 17,
        85 => 17,
        86 => 17,
        87 => 17,
        88 => 17,
        89 => 17,
        90 => 17,
        91 => 17,
        92 => 17,
        93 => 17,
        94 => 17,
        95 => 17,
        96 => 17,
        97 => 17,
        98 => 17,
        99 => 17
    ];

    /**
     * Handle incoming updates from users, chats and channels.
     */
    #[Handler]
    public function onAny($update): void
    {
        $chat_events = [
            'updateNewMessage',
            'updateNewChannelMessage',
        ];
        if (in_array($update['_'], $chat_events, true)) {
            /** ignore everything except messages */
            if (!isset($update['message']['message']) or !isset($update['message']['from_id'])) {
                return;
            }

            /** ignore old updates if bot was down some time */
            if (round(time() - $update['message']['date']) > 60) {
                return;
            }

            $this->handleMessageSend($update);
        }
    }

    protected function handleMessageSend($update): void{
        $message = $update['message']['message'];

        Logger::debug(Colors::$GRAY.'['.Colors::$DARK_GRAY.'Handle Message'.Colors::$GRAY.']: '.Colors::$WHITE.$message);

        $from_id = $update['message']['from_id'];
        $peer_id = $update['message']['peer_id'];
        $user_id = $this->wrapper->getAPI()->getSelf()['id'];

        $args = explode(" ", $message);
        $cmd = mb_strtolower($args[0]);
        array_shift($args);

        // Voice system
        if ($cmd === '.voice' && $from_id === $user_id) {
            if (!isset($args[0])) {
                $this->editMessage($update, 'Введите число от 1 до 25 для выбора музыки.');
                return;
            }
            if (!is_numeric($args[0])) {
                $this->editMessage($update, 'Введите число от 1 до 25 для выбора музыки.');
                return;
            }

            try {
                $voice_file = match((int) $args[0]) {
                    1 => ['file' => 'voices/52.mp3', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    2 => ['file' => 'voices/cadillac.mp3', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    3 => ['file' => 'voices/riptrappa.mp3', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    4 => ['file' => 'voices/vlubilas.mp3', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    5 => ['file' => 'voices/halloween.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],    
                    6 => ['file' => 'voices/venom.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    7 => ['file' => 'voices/wakeandbake.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    8 => ['file' => 'voices/Lo Siento.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    9 => ['file' => 'voices/tolerantnost.ogg'],
                    10 => ['file' => 'voices/soicynihao.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    11 => ['file' => 'voices/gimnrossii.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    12 => ['file' => 'voices/1hour.mp3', 'duration' => 99999999],
                    13 => ['file' => 'voices/retrorave.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    14 => ['file' => 'voices/benzomageddon.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    15 => ['file' => 'voices/dying2live.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    16 => ['file' => 'voices/pablo.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    17 => ['file' => 'voices/maniya.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    18 => ['file' => 'voices/papintank.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    19 => ['file' => 'voices/4x4.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM, 'duration' => 194], // не удаляйте duration, ато будет пизда времени
                    20 => ['file' => 'voices/hublot.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    21 => ['file' => 'voices/lyfe.ogg', 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    22 => ['file' => "voices/maksbetov1.mp3"],
                    23 => ['file' => "voices/maksbetov2.mp3"],
                    24 => ['file' => "voices/moybeliy.ogg", 'waveform' => self::BASSBOOSTED_WAVEFORM],
                    25 => ['file' => "voices/baller.ogg", 'waveform' => self::BASSBOOSTED_WAVEFORM],
                };
            }catch (\UnhandledMatchError) {
                $this->editMessage($update, 'Музыка не найдена! Введите число от 1 до 25.');
                return;
            }

            $this->deleteMessage($update);

            try {
                $this->messages->sendMedia([
                    'peer' => $peer_id,
                    'media' => [
                        '_' => 'inputMediaUploadedDocument',
                        'file' => $voice_file['file'],
                        'attributes' => [
                            ['_' => 'documentAttributeAudio', 'voice' => true] + (isset($voice_file['waveform']) ? ['waveform' => $voice_file['waveform']] : []) + (isset($voice_file['duration']) ? ['duration' => $voice_file['duration']] : [])
                        ]
                    ],
                    'parse_mode' => 'Markdown',
                ] + (isset($update['message']['reply_to']['reply_to_msg_id']) ? ['reply_to' => [ '_' => 'inputReplyToMessage', 'reply_to_msg_id' => $update['message']['reply_to']['reply_to_msg_id']]] : []));
            } catch(\Throwable $e) {
                $this->sendMessage(
                    $peer_id,
                    'Не удалось отправить голосовое сообщение!'
                );
            }
        }

        // Tag system
        if ($cmd === '.tagall' && $from_id === $user_id) {
            if (!DialogId::isSupergroupOrChannel($peer_id)) {
                $this->editMessage($update, 'Данную команду можно использовать только в беседах!');
                return;
            }

            if (!isset($args[0])) {
                $this->editMessage($update, 'Введите текст для тега!');
                return;
            }

            $chat_members = $this->wrapper->getAPI()->methodCallAsyncRead(
                'channels.getParticipants',
                [
                    'channel' => $peer_id,
                    'filter' => ['_' => 'channelParticipantsRecent'],
                ]
            );
            $count_members = $chat_members['count'];
            $count = $count_members / 200;

            $total_users = [];
            $offset = 0;
            for ($i = 0; $i <= round($count); $i++) {
                $members = $this->wrapper->getAPI()->methodCallAsyncRead(
                    'channels.getParticipants',
                    [
                        'channel' => $peer_id,
                        'filter' => ['_' => 'channelParticipantsRecent'],
                        'offset' => $offset,
                        'limit' => 200
                    ]
                )['users'];

                foreach ($members as $member) {
                    if ((in_array($member['id'], $members)) or ($member['deleted'] === true) or ($member['bot'] === true) or ($member['id'] === $user_id)) {
                        continue;
                    }
    
                    $total_users[] = $member['id'];
                }

                $offset += 200;
            }

            if (empty($total_users)) {
                $this->editMessage($update, 'В чате нет участников, которых можно упомянуть!');
                return;
            }

            $this->deleteMessage($update);

            $tag = implode(" ", $args) . "\n";

            $chunked_users = array_chunk($total_users, 5);
            foreach ($chunked_users as $users) {
                $message = '';
                $offset = 0;
                $mentions = [];

                foreach ($users as $user) {
                    $mentions[] = [
                        '_'       => 'messageEntityMentionName',
                        'offset'  => $offset,
                        'length'  => mb_strlen($tag),
                        'user_id' => $user
                    ];
                    $offset += mb_strlen($tag);
                    $message .= $tag;
                }

                $this->wrapper->getAPI()->methodCallAsyncRead(
                    'messages.sendMessage',
                    [
                        'peer' => $peer_id,
                        'message' => $message . "\n.",
                        'entities' => $mentions,
                        'parse_mode' => ParseMode::TEXT
                    ]
                );
            }
        }

        // Reaction system
        $poops = new Config("poops.yml", Config::YAML);
        if ($cmd === '.addpoop' && $from_id === $user_id) {
            if (!isset($update['message']['reply_to']['reply_to_msg_id'])) {
                $this->editMessage($update, '🚫 Нужен реплай');
                return;
            }

            $replied_message = $this->wrapper->getAPI()->methodCallAsyncRead(
                DialogId::isSupergroupOrChannel($peer_id) ? 'channels.getMessages' : 'messages.getMessages',
                [
                    'channel' => $peer_id,
                    'id' => [$update['message']['reply_to']['reply_to_msg_id']],
                ]
            );

            $poop_id = isset($replied_message['messages'][0]['from_id']) ? $replied_message['messages'][0]['from_id'] : $replied_message['messages'][0]['peer_id'];

            $poops->set($poop_id);
            $poops->save();

            $this->editMessage($update, '𝐍𝐚𝐬𝐫𝐚𝐥 𝐧𝐚 𝐭𝐞𝐛𝐲𝐚 𝐬𝐡𝐚𝐥𝐚𝐯𝐚 𝐞𝐛𝐚𝐧𝐚𝐲𝐚⛎');

            EventLoop::queue(fn() => $this->wrapper->getAPI()->methodCallAsyncRead(
                'messages.sendReaction',
                [
                    'peer' => $peer_id,
                    'msg_id' => $update['message']['reply_to']['reply_to_msg_id'],
                    'reaction' => [['_' => 'reactionEmoji', 'emoticon' => '💩']],
                    'big' => false,
                    'add_to_recent' => false,
                ]
            ));
        }
        if ($cmd === '.rmpoop' && $from_id === $user_id) {
            if (!isset($update['message']['reply_to']['reply_to_msg_id'])) {
                $this->editMessage($update, '🚫 Нужен реплай');
                return;
            }

            $replied_message = $this->wrapper->getAPI()->methodCallAsyncRead(
                DialogId::isSupergroupOrChannel($peer_id) ? 'channels.getMessages' : 'messages.getMessages',
                [
                    'channel' => $peer_id,
                    'id' => [$update['message']['reply_to']['reply_to_msg_id']],
                ]
            );

            $poop_id = isset($replied_message['messages'][0]['from_id']) ? $replied_message['messages'][0]['from_id'] : $replied_message['messages'][0]['peer_id'];
            if (!$poops->exists($poop_id)) {
                $this->editMessage($update, '𝐘𝐚 𝐢 𝐭𝐚𝐤 𝐧𝐞 𝐬𝐫𝐮 𝐧𝐚 𝐞𝐭𝐨𝐠𝐨 𝐜𝐡𝐞𝐥𝐨𝐯𝐞𝐤𝐚🏴☠');
                return;
            }

            $poops->remove($poop_id);
            $poops->save();

            $this->editMessage($update, '𝐎𝐛𝐨𝐬𝐫𝐚𝐥 𝐭𝐞𝐛𝐲𝐚 𝐬𝐥𝐢𝐭𝐚𝐲𝐚 𝐧𝐞𝐦𝐨𝐜𝐡⛎');
        }
        if ($cmd === '.clearpoop' && $from_id === $user_id) {
            foreach ($poops->getAll() as $poop_id => $value) {
                $poops->remove($poop_id);
            }
            $poops->save();

            $this->editMessage($update, '𝐁𝐨𝐥𝐬𝐡𝐞 𝐲𝐚 𝐧𝐢 𝐧𝐚 𝐤𝐨𝐠𝐨 𝐧𝐞 𝐬𝐫𝐮🥰❤️‍🔥');
        }
        if ($poops->exists($from_id)) {
            EventLoop::queue(fn() => $this->addReaction($update, '💩', false, false));
        }

        // Bulling system
        $bulling = new Config("bulling.yml", Config::YAML);
        if ($cmd === '.addbull' && $from_id === $user_id) {
            if (!isset($update['message']['reply_to']['reply_to_msg_id'])) {
                $this->editMessage($update, '🚫 Нужен реплай');
                return;
            }

            $replied_message = $this->wrapper->getAPI()->methodCallAsyncRead(
                DialogId::isSupergroupOrChannel($peer_id) ? 'channels.getMessages' : 'messages.getMessages',
                [
                    'channel' => $peer_id,
                    'id' => [$update['message']['reply_to']['reply_to_msg_id']],
                ]
            );

            $bull_id = isset($replied_message['messages'][0]['from_id']) ? $replied_message['messages'][0]['from_id'] : $replied_message['messages'][0]['peer_id'];

            $bulling->set($bull_id);
            $bulling->save();
    
            $this->editMessage($update, '☠️ Дай номер своей мамаши бездарь');
        }
        if ($cmd === '.rmbull' && $from_id === $user_id) {
            if (!isset($update['message']['reply_to']['reply_to_msg_id'])) {
                $this->editMessage($update, '🚫 Нужен реплай');
                return;
            }

            $replied_message = $this->wrapper->getAPI()->methodCallAsyncRead(
                DialogId::isSupergroupOrChannel($peer_id) ? 'channels.getMessages' : 'messages.getMessages',
                [
                    'channel' => $peer_id,
                    'id' => [$update['message']['reply_to']['reply_to_msg_id']],
                ]
            );
            if (!isset($replied_message['users'][0]['id'])) {
                $this->editMessage($update, 'Unknown error.');
                return;
            }

            $bull_id = isset($replied_message['messages'][0]['from_id']) ? $replied_message['messages'][0]['from_id'] : $replied_message['messages'][0]['peer_id'];
            if (!$bulling->exists($bull_id)) {
                $this->editMessage($update, '💀 Я и так не унижаю этого человека');
                return;
            }

            $bulling->remove($bull_id);
            $bulling->save();
    
            $this->editMessage($update, 'идешь нахуй слитая немощь⛎');
        }
        if ($cmd === '.clearbull' && $from_id === $user_id) {
            foreach ($bulling->getAll() as $bull_id => $value) {
                $bulling->remove($bull_id);
            }
            $bulling->save();

            $this->editMessage($update, 'Больше я никого не унижаю');
        }
        $messages = [
            "сиди терпи хуя Богосокрушительного уебаннское дитё отродья", "давай терпи здесь", "ты сынуля тупорылой ослыхи", "давай реще принимай мой хуй", "я тебя на член свой пущу", "ты тухлятина сперматозоидного хуя", "сынок говноедки ты, член хапай", "сынок ты шалавы ебаной а ну соси давай мне дабы я пощадил тебя моего раба", "ало дерьма кусок куда ты там потерялся", "я же ещё не повеселился ну же тупая пизда старой гнилой хуиты", "мне тя че долго ждать или как свинтус ебаный я же тебя предупредил что со мной лучше не связываться а то можешь и ел ебалу получишь понимаешь ли ты что мне поебать на тя только ты не потухай", "я тебя ебашу даже на раслабоне слушай а попытайся мне покзаать тут свой словарный запас пока я не разозлился и не дал тебе пизды сын шлюхи матери", "лал ты чо тут потух что ли ебнутый сын твари ты тут давай не помри мне изи ебал тя ты чо там уснул что ли", "ало не падай духом ебаный слабачок буду тебе щас твои органы вырывать и консервировать их на дикой скорости как твоя лысая душевнобольная бабка ммой член сосала", "да ты ж помнишь как я твою мать к стулу привязывал и играл с ней в больно или не больно ну чаще всего это слабая свинья орала во всю глотку от боли ну это значит что я грамотно ебашил ее по голове монтировкой дробя ее череп тупой бляди", "ты чо тут помер что ли харе без негатива мне сосать", "ты же ебаная слабота которую я не буду жалеть и просто напросто убью тя ты чо тут что ли ебнутый тюфяк уже заткнулся не дай бог ты ща свои копыта откинешь", "ай ебучий слабак гнилой потухший от удара моего кулака в свое рыло ты думаешь я тя тут жалееть буду или чо", "ты же просто отбитый дерьмоедный червяк которого я раздавлю берцами где вместо шнурков будут героиновые артерии твоей матери шлбзи я те ща  тут внатуре вколю в шею шприц с нуфалином", "ты очнулся на больничной койке где я буду вырезать твои лёгкие ",
            "закрой рыло своё сын шлюхи",
            "хуяру зажуй тебе сказали ты чё тупого из себя строишь",
            " мамашу твою ебал сынок шлюхи ты хехе ", " поимел тебя сынка шлюхи ебаного прикинь ",
            " ты мне жестко отсосал щас или че хуесаска лалка ",
            " блять вот у тебя мамаша шлюха ебучая ", " вот твою мамашу ебали а ты даж ниче не сказал лошок ",
            " спермоглист, ебучку втопи ",
            " пахай на моем члене, раб",
            " я тебе свой хуй кинул как фрисби", " пес ебаный ты нахуй отсосал", " лох ебаный ты че потух опять ",
            " переломали тебя лошара ебучая",
            " обхуесосим тебя боребух ебаный", " отсосешь по полной, макака ебучая",
            " почему ты хуй сосал ало блять ", " слыш полуфабрикат отсоси мне ",
            " твоя мать так то по всем статьям шлюха ", " отсосешь мне хуесоска ебучая не пытайся даже сопротивление давать ",
            " пизда я твою мамашу в рот ебал ты в кусре надеюсь ", " я твою мать в комбаине имел бля ",
            " перехуярим тебе еблище лошара ебучая даже не думай отсосать мне хуеплетка малоразумная ",
            " обосу тебе ебальник хуесоска ебучая ", " шлюхотень подзаборная не отсоси мне ",
            " переломаем тебе еблище рваная курва ", "обойму спермы в тебя запустил хуйло ",
            " нассали тебе на патлы хуйло",
            " насру тебе в гортань сынок шлюхи ", " обосру тебе еблище осел ебаный ",
            " сынок шлюхи тя хуем тут по ребрам ебарирую", " ну ты внатуре козел ебать я те рога вырвал",
            " пососал ты моего члена конкретно щас олух ты сук", " ебать ты барсук я тебя в рот ебал ",
            " в гриву тебя ебал ", " в ротик тебя имею шлюшка ", " поимел тебя под фонк ",
            " под накротиками тебя имел ", " под пивасом тебя имел ", " под пивандеполой тебя имею ",
            " под диваном тебя имел ", " под кроваткой тебя имел ", " пол одеялом тебя трахал тайно ",
            " как ниндзя тебе в очко закидал свой хуесюрикен ", " в отсосе ты остался лошара сук ",
            " пососи мои яйца щенок ты сук) ", " слыш меня клоун ебал я тебя в нос твой красный ",
            " слыш блять хуесоска я тебя в рот ебал лалку ", " твою мамашу милфу на секс раскрутил прикинь",
            " пизду твоей мамаши раскрутил ", " натянул твою мать как стрелу на тетеву нах ",
            " твою мать натягивал на свой хуй пока не заметил что она уже труп ",
            " пока твоя мать сосала я пил пиво ", " раньше твою мать в три щели ебали теперь она хочет больше не можем отказать этой шалаве и зовем толпы на ее пизду", " членом тебе мамашу ебал",
            " в бане парился и твою мать ебал там же ",
            "обоссали тебя щенок ебаный хе-хе ", " че ты мне сосешь дура ебаная ",
            " обоссал тебя хуйло лошара ебаная ", " сру тебе на рожу чмо ебаное ",
            " да ты уже в отсосе телочка ебаная", " твяо мать мой хуй любит что пиздец",
            " я раскромсал еблище твоей полугнилой матери",
            " слыш ты шелуха ебаная метнулся отсюда кабанчиком нахуй ",  " разложившееся мясо твоей мамаши ебал на крышке гроба твоего отца", 
            " на костях твоей мамаши построил себе дом",
            " кости твоей мамаши друг о друга заточил теперь буду резать тебя свинья ебаная",
            " я подтолкну к тебе солнце и сожгу тебя нахуй",
            " крики твоей мамаши среди пыточных инструментов заводит мой хуй на убойную атаку в ее ебальник",
            " изуродованную твою мать ебал", " почерневший скелет твоей мамаши ебал",
            " выкопал выродка твоей мамаши из ее пизды , отрезал ему голову и засунул ей в пизду",
            " до отказа забиваю твое горло говном", "выкидываю требуху твоей мамаши тебе в ебало",
            " ебу твою мать как обычно среди темного покрова", " гниющее ебало твоей мамаши ебал",
            " твой разум окутал своим членом",
            " че говна сожрал выкидыш беременной селёдки",
            " еще че то скажи сюда сын говна и всю оставшуюся жизнь ты будешь передвигаться рывками",
            " ле твоесть я твою мать ебал внатуре да ",
            " ты не понял но я твою мать ебал",
            "я твою маму ебал сынка шлюхи кривоёблого",
            "ты здесь мою залупу отсосёшь немощь ебучая блять",
            "я твоё ебало щас разложу здесь и каждый будет проходить и об тебя ноги вытирать сынка проститутки такого",
            "я твою маму ебал криворукого сынка проститутки бля ахаха",
            "че замолк идиот ебаный мать те ебём",
            "поймай член ебалом чунга чанга ты ебаная",
            "твоя мать шлюха соберись давай с силами идиот ебаный и придумай хоть какие то слова сын шлюхи зашуганный",
            "иди нахуй отсюда говорю сынок проститутки пока я и тебе ебало нахуй не снес своей залупой ахаха",
            "в соло всей конфе хуи до блеска соси блядина слабая",
            "ку сосёшь слабак криворукий",
            "член мой на вкус попробуй сын шлюхи тебе он на всю жизнь запомнится тут придурок ебаный блять ахахаха",
            "всем селом тебя негры ебали",
            "ниже отпиши если твоя мать шлюха",
            "сосу хуй в теле твоей мамаши, в хуй спиздани ниже",
            "при помощи души твоего отца, я убил твою мать мелким хуем твоего отца, и после чего расчленил на маленькие кусочки",
            "докажи что твоя матуха шлюха любым сообщением ниже",
            "при помощи души твоего отца я убил твою мамашу шлюху и после чего я отдал ее мертвое тело хачам за пачку сигарет",
            "давай ты всей конфе докажешь что ты мой фанат, просто отпиши ниже любое сообщение",
            "ты же ебаный фанатик моего хуя)) просто признай, что всосал мне анскильный ты хуесос я тя ебу словами как и твою матуху ниже кстати убейся об залупу",
            "ты тупоголовый сельский житель, переставай уже провацировать мой хуй на еблю твоей мамаши",
            "ты знаешь какие отличия между твоей мамашей и хуем хача? Они незаменимы",
            "добей свою матуху сообщением ниже, а то она уже не может сосать мне хуй",
            "всоси ниже текстом сынок ускоглазой",
            "я тя парой слов ебу, пока ты потеешь мне в залупу",
            "воздух = мой член, дыши глубже",
            "чо папе в залупу спизданул уёбище?",
            "с какими словами я убил твою матуху, отпиши ниже",
            "ты потный хряк, соси ниже словами",
            "ХАХАХАХАХ бездарный хуесос я же тя парочкой слов выебу пока ты текста будешь сочинять тупоголовый деревенский житель продолжай фанатеть от моей залупы",
            "ты обрыганый сын московского бомжа",
            "с какими словами твоя мамаша сосала?",
            "взял хуй в рот, он сказал:",
            " я матери твоей гвозди в ногти на ногах и руках буду забивать пока она от боли не сдохнет",
            " сынка шалавы тебя тут каждый второй хуесосит, сделай что нибудь с этим терпила ебаная",
            " лови битой по ебальнику своему тупому сынок шлюхи запиздевшийся",
            " сын шлюхи заглатывай мой здоровенный хуй по самые свои гланды",
            " меня не ебёт твой статус в обществе, для меня ты как был сынком шлюхи так и остался",
            " я твою мамашку ебал ты сын шалавы запомни это на всю свою жалкую жизнь",
            " захарканое ебалище твоё в щепки разъебал одним ударом залупы",
            " я тебе ебало твоё грязное тут к хуям собачьим переломаю на запчасти",
            " сынок шлюхи ты свои цирковые навыки будешь в другом месте показывать, но не тут, так что хуй отсасывай мне",
            " ты нахуй уже аккаунты меняешь шпион ебаный думал я тебя не узнаю сынка шлюхи",
            " я твоей мамаше горло вскрыл нахуй, что у неё кровь струей сочилась из вен",
            " мы твою мать на рейдах разорвём и пустим на ленты",
            " че ты там спиздануть хотел сын шлюхи чернющий",
            " ебашу твой сосальник об край стола в кровь",
            " я твоей матери голову выбил с позвоночника оперкотом",
            " глотку твоей матери шалаве перееду нахуй",
            " ты сынуля шалавы ебанный ты чё тут о себе нахуй возомнил, давай иди нахуй отсюда пока я тебя тут просто не убил сынка шалавы",
            " я твоей мамаши ножом голову из пазов вырву",
            " отсоси хуец ебаная шалава",
            " просверлю дырку в сосальнике твоей мамаши и закину туда крыс, чтобы твоя мать сгнила изнутри",
            " на гроб твоей матери насру, сын шалавы ты",
            " вырвем позвонки твоей жалкой мамаше",
            " харкали в трупешник твоей мамаши шлюханки ебанной",
            " ты пешка ебаная, чисто хуй мой отсосешь как и всегда в принципе",
            " я щас тебе калитку нахуй снесу сынок шлюхи",
            " я глаза твоей матери хуем выдавил чисто ебать ты пешка ебанная просто снова всё стерпишь",
            " с кентами собьем ебальник твоей мамаши шлюханки дешёвой",
            " отверткой пробил позвоночник твоей мамаше шлюхи",
            " твоя прыщавая мамаша снова у меня хуй соснула",
            " в труп твоей мамаши затолкаем вырванные органы твоего отца",
            " не выёбывайся щенок ебанный ты так и так тут будешь сосать мне хуй до талого ты меня понял нет",
            " поставлю крест на карьере твоей шлюхи матери",
            " да чё ты тут грозишься сын шалавы ебанный, отсоси мне хуй лучше",
            " твоя мать шалава ебанная которая мне сегодня отсосёт хуй и на этом точка",
            " я ставлю точку на ебальнике твоей матери не смываемым маркером за каждый отсос, вот поэтому у неё ебальник весь в точках",
            " да закрой ебальник терпила ебанная, кому ты чё тут сделаешь, давай соси мой хуй",
            " рой себе могилу ебучий сын бляди, я твою семью детей шлюх уничтожу нахуй просто",
            " я тебе кишки здесь выжму сын шлюхи ты ебучий",
            " что ты можешь сын шлюхи, тебя тут в первую очередь поставлю на колени",
            "я украл твою душу, дабы незаметно убить твою мать шлюху когда она будет ебаться с хачами в соседней комнате кстати в хуй че спизданешь?",
            "ублюдощный хуесос, твои текста сразу идут мне в залупу старайся лучше",
            "Мой хуй для твоей мамы как спайдерман,по ночам по крышам лазит до нее,а твой отец думает что за шумы <у моей жены в комнате> а там я ее ебу)",
            "с этой провокацией на тебя кончал твой брат а ты что зеркалу орал?",
            "с этой провокацией твоя мать ебала тебя страпаном в анал ,а ты что ей орал когда хуй отца досасывал?",
            "с этой провокацией тебя добили хачи, а ты слизывал у них с залупы кончу и что отцу орал?",
            "с этой провокацией ты вылизал клитор сестре, а та обоссала твое ебло и что тебе ответила после члена моего?",
            "с этой провокацией твой отец кончал  на твою сестру а ты слизывал кончу и что зеркалу орал?",
            "с этой провокацией твоя мать заглатывала мой член как питон, а ты что отцу орал когда хуй бомжа  всасывал?",
            "с этой провокацией ты откусил клитор своей матери ,а та обоссала тебя и  что тебе крикнула?",
            "с этой провокацией твой отец прищимил твой хуй дверью, а ты укусил его за залупу и что ему крикнул?",
            "с этими словами я твою мать в подвале ебал, а ты сосал член моему псу и что отцу своему орал?)",
            "с этими словами ты ебал собак, а твоя мать облизывала мне яйца и что тебе орала?)",
            "Лол, меня пригласили в конфу просто пообщаться, а уже через минуту я ебу какую то шлюху... ты понимаешь что я своим хуем накрыл клиторе твоей матери как волной цунами ?",
            "блять как не зайдешь в магазин там твоя мать тупая сидит на коленях и у прохожих сосёт за деньги, вот блять откуда у вас в доме хлеб, оказалось твоя мать зарабатывает тяжелым трудом",
        ];
        if ($cmd === '.bullr' && $from_id === $user_id) {
            $this->editMessage($update, $messages[array_rand($messages)]);
        }
        if ($bulling->exists($from_id)) {
            EventLoop::queue(fn() => $this->sendMessage(
                ($peer_id === $user_id) ? $from_id : $peer_id,
                $messages[array_rand($messages)],
                ParseMode::TEXT,
                $update['message']['id']
            ));
        }
    }

    public function editMessage(array $update, string $message, ParseMode $parseMode = ParseMode::TEXT, array $extraData = [])
    {
        return $this->wrapper->getAPI()->methodCallAsyncRead(
            'messages.editMessage',
            [
                'peer' => $update['message']['peer_id'],
                'id' => $update['message']['id'],
                'message' => $message,
                'parse_mode' => $parseMode,
            ] + $extraData
        );
    }


    /**
     * Delete the message.
     *
     * @param array   $update
     * @param boolean $revoke Whether to delete the message for all participants of the chat.
     */
    public function deleteMessage(array $update, bool $revoke = true): array
    {
        return $this->wrapper->getAPI()->methodCallAsyncRead(
            DialogId::isSupergroupOrChannel($update['message']['peer_id']) ? 'channels.deleteMessages' : 'messages.deleteMessages',
            [
                'channel' => $update['message']['peer_id'],
                'id' => [$update['message']['id']],
                'revoke' => $revoke,
            ]
        );
    }

    /**
     * Add reaction to message.
     *
     * @param array      $update
     * @param string|int $reaction    reaction
     * @param bool       $big         Whether a bigger and longer reaction should be shown
     * @param bool       $addToRecent Add this reaction to the recent reactions list.
     *
     * @return list<string|int>
     */
    public function addReaction(array $update, int|string $reaction, bool $big = false, bool $addToRecent = true): array
    {
        return $this->wrapper->getAPI()->methodCallAsyncRead(
            'messages.sendReaction',
            [
                'peer' => $update['message']['peer_id'],
                'msg_id' => $update['message']['id'],
                'reaction' => \is_int($reaction)
                    ? [['_' => 'reactionCustomEmoji', 'document_id' => $reaction]]
                    : [['_' => 'reactionEmoji', 'emoticon' => $reaction]],
                'big' => $big,
                'add_to_recent' => $addToRecent,
            ]
        );
    }
}