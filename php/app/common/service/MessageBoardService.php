<?php

declare(strict_types=1);

namespace app\common\service;

use think\facade\Db;

use app\common\service\BaseService;
use app\common\model\MessageBoard;

class MessageBoardService extends BaseService
{
    /**
     * @param Message $model
     */
    public $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new MessageBoard();
    }

    /**
     * 列表
     */
    public function list($data)
    {
        $pageNo = isset($data['pageNo']) ? (int) $data['pageNo'] : 1;
        $pageSize = isset($data['pageSize']) ? (int) $data['pageSize'] : 10;
        $to = $data['to'] ?? false;
        $isName = $data['isName'] ?? false;
        if (!$to) {
            $message = '请填写名称或暗语';
            $code = -1000;
            throw new \think\Exception($message, $code);
        }

        $order = [
            'mb.create_time' => 'desc'
        ];
        $where = [
            ['mb.to', '=', trim($data['to'])]
        ];

        $total = $this->model
            ->alias('mb')
            ->where($where)
            ->count();
        $model = $this->model
            ->alias('mb')
            ->where($where)
            ->order($order)
            ->limit($pageSize)
            ->page($pageNo)
            ->field(['mb.message_board_id', 'mb.from', 'mb.to', 'mb.content', 'mb.create_time'])
            ->select()
            ->toArray();
        foreach ($model as &$v) {
            $v['avatar'] = 'https://assets.yqt.life/web/wechat-toolbox/images/avatar/01.jpg';
        }
        unset($v);

        if (count($model) === 0 && $isName) {
            // return $this->createForMock($data);
        }
        return [
            'list'       => $model,
            'pageSize'   => $pageSize,
            'pageNo'     => $pageNo,
            'totalPage'  => ceil($total / $pageSize),
            'totalCount' => $total,
        ];
    }


    /**
     * 详情
     *
     */
    public function find($data)
    {
        $id = $data['message_board_id'];
        $model = $this->model->find($id);
        $model = $model === null ? false : $model->toArray();
        if (!$model) {
            $message = '查询失败';
            $code = -1000;
            throw new \think\Exception($message, $code);
        }

        return $model;
    }

    /**
     * 创建
     *
     */
    public function create(array $data)
    {
        Db::startTrans();

        try {
            $model = MessageBoard::create($data);
            $model = $model === null ? false : $model->toArray();
            Db::commit();
            return $model;
        } catch (\Exception $e) {
            Db::rollback();
            $code = $e->getCode() ? $e->getCode() : -1000;
            throw new \think\Exception($e->getMessage(), $code);
        }
    }
    public function createForMock(array $data)
    {
        Db::startTrans();

        try {
            $data['type'] = 'mock';
            $data['content'] = $this->getMockData();
            $data['isName'] = false;
            $data['create_time'] = time() - mt_rand(600,7200);
            $model = MessageBoard::create($data);
            Db::commit();
            return $this->list($data);
            // return $data;
        } catch (\Exception $e) {
            Db::rollback();
            $code = $e->getCode() ? $e->getCode() : -1000;
            throw new \think\Exception($e->getMessage(), $code);
        }
    }
    public function getMockData()
    {
        $map = ["我总觉得自己不够好 我不知道我自己有没有优秀到可以和你在一起", "这次不会把你抛下了", "能不能回头看看我", "你个大傻子 我一直在等你", "二十岁能住进你的梦希望八十岁能与你相伴", "我对你可是有图谋的，想图你一辈子大傻子", "生气归生气，又不是不喜欢你。我在等你哄我", "我带了一书包的零食，大家都可以吃，只有你可以吃完", "我们在一起吧，行就行，不行我再想想办法", "我真的认识你好久了看你一直把我当基友，可我不是", "你平安快乐就好了", "希望你和我永远在一起 ", "倘若我对你问心有愧呢", "可我对你不是一时兴起啊", "我就是赌一把你会搜自己", "一直一直陪伴你 要岁岁平安哦", "遇见你不容易 错过了很可惜 我想和你好好在一起", "你胜过一百个泛泛之交", "哈哈哈哈哈我知道你肯定回来看", "不要再emo了记得我一直在 ", "我真的很想你", "嘎嘎嘎哈哈哈爱你猜猜我是谁无奖竞猜", "你呀，是我这么久以来最喜欢的一个人没有之一，我就想默默守护你", "对于我，你有没有遗憾", "6666碉堡了", "傻子 就知道你会搜索 哈哈哈哈", "我想知道你到底对我有没有心动", "我爱过你的 你要记得", "等我一下，就一下", "祝你快乐，不仅仅是今天勇敢爱，勇敢享受，快乐是一天，不快乐也是一天。", "是同名啊！", "加油鸭", "希望事事顺利", "我错啦我真的后悔了可我自尊心太强", "最近总是想起你，不知道是不是习惯，我也对别人动心过，但是就是那么一小段时间，什么都没有，我无数次想起你，那种感觉让我陌生又害怕。也许吧，你就是我的心魔。", "最可笑的是，我们连联系方式都没有了。", "念念不忘直到现在", "你说， 我念念不忘，真的会有回响吗", "你问我的那个问题，我的答案是，是。", "和我同名，被那么多人念叨着，真好。", "在我的名字里看到了别人的青春，羡慕", "我想你了，真的很想很想", "认识好久了，有机会，再见一面吧", "我最后悔的事情是没有勇敢 说那一句 我喜欢你", "你还年轻，你可以大胆一点，不用想太多", "希望你快快乐乐长大 直到永远", "在你身边的时候我从来没有羡慕过任何人", "如果有时间 ，你会开看一看我吗？", "祝福你平安，不如祝福你幸福", "我没有在等你了，但是我也没喜欢上任何人", "他们说，我再继续那样下去很不礼貌 ", "l O U", "我是你爸爸", "姐姐希望你找到一个你爱的他也爱你的对象", "你还好吗？我已经快不记得你的声音了。记忆也慢慢消失，希望我们以后可以顺顺利利的，再见了，我要努力一个人生活了", "我xh你", "你是大sb 气死你", "我好想你 我的宝贝 曾经的宝贝", "以后 希望一直都在 一年四季 ", "即使生生不见 也要日日平安", "你快点暴富好吗~~~~~~等你富起来包养我", "在外面不要随便相信别人的话，也不要太容易动心，有什么不开心的和我说，我一直都在。", "岁岁平安 念念有你", "你一定要优秀 挣好多钱！！！！", "希望我能够被爱和开心 ", "是岁岁年年呀", "疫情快点结束好不好我想你了", "永远永远永远永远永永远永远永远永远永远永远永远永远永远！", "爱你 爱你", "笨蛋 我永远永远在你这里", "我们还会有机会吗 倘若我没你不行呢", "爸爸爱你 傻儿子", "辜负真心的人是要吞一千个针尖的 但是我舍不得你这样 我帮你吞了吧 就这样吧  以朋友的名义 也别记住我了 ", "宝宝我一直在的", "我还爱你", "你好吊哦 ", "对不起 我出于精神上的问题和你分手 我不敢和你说 希望你开心", "宝贝我好爱你呀嘿嘿", "你的生日要到了可我不能陪你过了。遗憾吗？也许吧。", "我真的好想你啊傻猪"];
        $rand = array_rand($map);
        return $map[$rand];
    }

    /**
     * 删除
     *
     * @return bool
     */
    public function del($data)
    {
        $id = $data['message_board_id'];
        // Db::table('message_board')->where([['message_board_id', '=', $id]])->delete();

        return true;
    }
}
