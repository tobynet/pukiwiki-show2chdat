<?php
// show2chdat�ץ饰����
// $Id: show2chdat.inc.php,v 1.20 2005/11/12 14:06:12 m-arai Exp $
// 2�����ͤ뷿DAT�ե����뻲�ȥץ饰����
//
// #show2chdat( id[, num1[, num2,....]])
//
//  id  : ����åɥ����ȥ����ϥ���åɥ���
//  num : �����ֹ�
//        10     10
//        12-    12�ʹ�
//        14-15  14��15
//        ��ά   ����
//

//DAT���� (������'<>'�ο�)
define(	'TYPE_2CH',	4);
define(	'TYPE_JBBS',	6);

// Ⱦ�ѥ��ʤ����Ѥ��Ѵ�����
define('SHOW2CHDAT_KANACONV', TRUE);
// ��������ʸ��������ʸ�����Ѵ��򤹤�
define('SHOW2CHDAT_KIGOCONV', TRUE);

// �������󥯤򥢥󥫡��� (���Υ쥹���ڡ�����ˤ���Ȥϸ¤�ʤ�)
define('NUMLINK_ANC', FALSE); // FALSE�ξ�硢�������󥯤��оݥ쥹��ɽ�������󥯤ˤʤ�

require_once(PLUGIN_DIR.'dat2ch.inc.php');

function show2chdat_kigopreg_ini()
{
	global $show2chdat_ptn,$show2chdat_rpl;
	$kigotbl = array(
	'\x87\x40'=>'(1)',
	'\x87\x41'=>'(2)',
	'\x87\x42'=>'(3)',
	'\x87\x43'=>'(4)',
	'\x87\x44'=>'(5)',
	'\x87\x45'=>'(6)',
	'\x87\x46'=>'(7)',
	'\x87\x47'=>'(8)',
	'\x87\x48'=>'(9)',
	'\x87\x49'=>'(10)',
	'\x87\x4a'=>'(11)',
	'\x87\x4b'=>'(12)',
	'\x87\x4c'=>'(13)',
	'\x87\x4d'=>'(14)',
	'\x87\x4e'=>'(15)',
	'\x87\x4f'=>'(16)',
	'\x87\x50'=>'(17)',
	'\x87\x51'=>'(18)',
	'\x87\x52'=>'(19)',
	'\x87\x53'=>'(20)',
	'\x87\x54'=>'I',
	'\x87\x55'=>'II',
	'\x87\x56'=>'III',
	'\x87\x57'=>'IV',
	'\x87\x58'=>'V',
	'\x87\x59'=>'VI',
	'\x87\x5a'=>'VII',
	'\x87\x5b'=>'VIII',
	'\x87\x5c'=>'IX',
	'\x87\x5d'=>'X',
	'\x87\x5f'=>'m',
	'\x87\x60'=>'k',
	'\x87\x61'=>'c',
	'\x87\x62'=>'m',
	'\x87\x63'=>'g',
	'\x87\x64'=>'t',
	'\x87\x65'=>'a',
	'\x87\x66'=>'ha',
	'\x87\x67'=>'l',
	'\x87\x68'=>'W',
	'\x87\x69'=>'cal',
	'\x87\x6a'=>'$',
	'\x87\x6b'=>'c',
	'\x87\x6c'=>'%',
	'\x87\x6d'=>'mb',
	'\x87\x6e'=>'page',
	'\x87\x6f'=>'mm',
	'\x87\x70'=>'cm',
	'\x87\x71'=>'km',
	'\x87\x72'=>'mg',
	'\x87\x73'=>'kg',
	'\x87\x74'=>'cc',
	'\x87\x80'=>"'",
	'\x87\x81'=>',,',
	'\x87\x82'=>'No.',
	'\x87\x83'=>'K.K.',
	'\x87\x84'=>'TEL',
	'\x87\x8a'=>'(��)',
	'\x87\x8b'=>'(ͭ)',
	'\x87\x8c'=>'(��)',
	'\x87\x90'=>'??',
	'\x87\x91'=>'??',
	'\x87\x92'=>'??',
	'\x87\x95'=>'??',
	'\x87\x97'=>'<',
	'\x87\x9a'=>'??',
	'\x87\x9b'=>'??',
	'\x87\x9c'=>'??',
	'\xee\xef'=>'i',
	'\xee\xf0'=>'ii',
	'\xee\xf1'=>'iii',
	'\xee\xf2'=>'iv',
	'\xee\xf3'=>'v',
	'\xee\xf4'=>'vi',
	'\xee\xf5'=>'vii',
	'\xee\xf6'=>'viii',
	'\xee\xf7'=>'ix',
	'\xee\xf8'=>'x',
	'\xee\xfa'=>'|',
	'\xee\xfb'=>"'",
	'\xfa\x54'=>'??',
	'\xfa\x57'=>'??',
	);

	// �󤤤ʡ� ;-<
	$i = 0;
	foreach ( $kigotbl as $key=>$val ) {
		$show2chdat_ptn[$i] = '/([^\x81-\x9f\xe0-\xff])'.$key.'/';
		$show2chdat_rpl[$i] = '$1'.$val;
		$i++;
	}
}

function show2chdat_entities( $line)
{
	static $esc = array("\1","\2"),$esc_r = array('&',';');

	// ��󥯤����������Ƥ��ޤ�
	$line = preg_replace('/<a href=".*?>(.*?)<\/a>/i', '$1', $line);

	// \1��\2��&hoge;�򲾤˥��������פ���
	$line = str_replace( $esc,' ', $line); //ǰ�ΰ١�������\1,\2���ڤ�		
	$line = preg_replace( '/\&(\w+);/',"\1\$1\2", $line);

	// ���Ԥ����̤˰��äƤ���
	$line = str_replace('<br>',"\n", $line);

	// ����¾��htmlentities�к�
	$line = htmlspecialchars($line);

	// ���Υ��������פ�����
	$line = str_replace($esc, $esc_r, $line);

	// make_link()�Ϥ����ǻȤ��ˤϼ㴳���꤬���뤿�ᡢ�����ø��ʥ������
	$line = preg_replace(
			'/(http|ttp):\/\/([\/\w\-\.,@?^=%\&:;~]+)/',
			'<a href="http://$2">$1://$2</a>',
			$line );
	$line = preg_replace(
			'/(https|ttps):\/\/([\/\w\-\.,@?^=%\&:;~]+)/',
			'<a href="https://$2">$1://$2</a>',
			$line );

	// ���Ԥ��᤹
	$line = str_replace("\n", '<br />',$line);

	// ����&hoge;�򻦤�
	$line = preg_replace('/\&(rlo|lro);/i', '&amp;$1;',$line);

	return $line;
}

function show2chdat_getrange($ranges)
{
	foreach ( $ranges as $rl ) {
		$rl = trim($rl);

		if ( preg_match('/^\d+$/', $rl)) {
			$ret[] = array('st'=>$rl,'ed'=>$rl);
		} else if ( preg_match('/^(\d+)\-$/', $rl, $mc)) {
			$ret[] = array('st'=>$mc[1]);
		} else if ( preg_match('/^\-(\d+)$/', $rl, $mc)) {
			$ret[] = array('st'=>1,'ed'=>$mc[1]);
		} else if ( preg_match('/^(\d+)\-(\d+)$/', $rl, $mc)) {
			$ret[] = array('st'=>$mc[1],'ed'=>$mc[2]);
		} else if ( preg_match('/^(\d+)\+(\d+)$/', $rl, $mc)) {
			$ret[] = array('st'=>$mc[1],'ed'=>($mc[1]+$mc[2]-1));
		}
	}

	return $ret;
}

function plugin_show2chdat_action()
{
	global	$vars;

	$opts = explode(',', $vars['num']);
	array_unshift($opts, $vars['id']);
	$body = call_user_func_array( 'plugin_show2chdat_convert',$opts);
	return array('msg'=>$vars['id'],'body'=>$body);
}

function plugin_show2chdat_inline()
{
	global	$script;

	$args =  func_get_args();
	$alt = array_shift($args);


	$id  = array_shift($args);
	$num = implode(',',$args);
	$alt = $alt ? $alt: htmlspecialchars("$id [$num]");

	$id = rawurlencode($id);
	$num = preg_replace('/(?![\d-,])/','',$num);

	return "<a href=\"$script?plugin=show2chdat&amp;id=$id&amp;num=$num\">$alt</a>";
}

function plugin_show2chdat_convert()
{
	global $vars,$show2chdat_ptn,$show2chdat_rpl,$script;
	static $res;

	$args =  func_get_args();

	// ���ѥ�᡼���ϥ���åɥǡ������̻�
	$id = trim( array_shift($args));

	// ����ѥ�᡼���ʹߤ�ɽ���쥹�ֹ�
	if (!($ranges = show2chdat_getrange($args))) {
		// ����̵���������� ( '1-'������Ʊ��)
		$ranges[0] = array('st'=>1);
	}

	if ( !is_readable(
		($fname = UPLOAD_DIR.encode($vars['page']).'_'.encode($id)))) {
		// ���̻Ҥ����ڡ�����ź�եե�����̾�ʤ餽���ͥ��
		check_readable(DAT2CH_ARCPAGE); // �ɤߤ������³�ǧ
		$fname = dat2ch_get_datfilename($id);
	}

	// ���ɤʤ������ɤޤʤ�
	if ( !$res[$fname] ) {
		SHOW2CHDAT_KIGOCONV && show2chdat_kigopreg_ini();

		if (!($rdata = gzfile($fname))) {
			return '<div>not found.</div>';
		}

		list(,$idbase) = explode('_',basename($fname));

		// �쥹���󥯤��ִ��ѥ��������
		if ( NUMLINK_ANC ) {
			$npat = '/&gt;&gt;(\d+)/';
			$nrpl = "<a href=\"#r{$idbase}_\$1\">&gt;&gt;\$1</a>";
		} else {
			$eid = rawurlencode($id);
			$npat =	'/&gt;&gt;(\d+-\d+|\d+-|\d+)/';
			$nrpl = "<a href=\"$script?plugin=show2chdat&amp;id=$eid&amp;num=\$1\">&gt;&gt;\$1</a>";
		}

		$i = 1;
		$mode = substr_count($rdata[0],'<>');
		$code = $mode == TYPE_2CH ? 'SJIS': 'auto';
		foreach ( $rdata as $resl ) {
			// �����к�
			if ( $resl == "\n" ) continue;

			// �ü�ʸ�����ִ�
			if ( SHOW2CHDAT_KIGOCONV ) {
				$resl = preg_replace( $show2chdat_ptn, $show2chdat_rpl, $resl);
			}

			switch ($mode) {
				case TYPE_2CH: // 2ch DAT����
					list($name,$addr,$date,$body) = explode('<>', $resl);
					break;
				case TYPE_JBBS: // JBBS rawmode����
					list(,$name,$addr,$date,$body) = explode('<>', $resl);
			}

			// ���ԥ����������к�
			if ( (!$name) && (!$addr) && (!$date) && (!$body) ) {
				continue;
			}
			
			list($name,$trip) = explode('&lt;/b&gt;', show2chdat_entities($name));
			$name = "<span style=\"color:green;font-weight:bold;\">$name</span>";
			if ( $trip ) {
				$name .= '<span style="color:green;">'.
							substr($trip,0,-10).'</span>';
			}

			$body = preg_replace( $npat, $nrpl, show2chdat_entities($body));

			if ( $addr ) {
				$mpre = '<a href="mailto:'.show2chdat_entities($addr).'">';
				$msuf = '</a>';
			} else {
				$mpre = '';
				$msuf = '';
			}

			$res[$fname][$i] = mb_convert_encoding(
				'<dt'.(NUMLINK_ANC?" id=\"r{$idbase}_{$i}\"":'').">$i : $mpre$name$msuf : $date</dt><dd>$body<br /> <br /></dd>",
				SOURCE_ENCODING, $code);

			$i++;
		}
	}

	foreach ( $ranges as $rg ) {
		if (!$rg['ed']) {
			$rg['ed'] = count($res[$fname]);
		}
		for ( $i=$rg['st']; $i<=$rg['ed']; $i++) {
			$ret .= $res[$fname][$i]."\n";
		}
	}

	if ( SHOW2CHDAT_KANACONV )
				$ret = mb_convert_kana( $ret, "KV", SOURCE_ENCODING);

	return '<div><dl>'.$ret.'</dl></div>';
}?>