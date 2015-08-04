<?
ini_set('display_errors', true);
prt("start");

//$sFile = "web-Google";
$sFile = "roadNet-TX";

//prt($sContent);
prt( file_exists("$sFile.txt") );

$readRs = fopen("$sFile.txt",'r');

if($readRs == false){
	prt("source open failed");
	exit;
}else{
	prt("source open success");
}

//skip first four line
for($i=0; $i<4; $i++){
	$sLine = fgets($readRs);
	prt($sLine);
}

$aNodes = array();
$aLinks =  array();
$iCount=0;

while($sLine = fgets($readRs) ){
	
	$sLine = str_replace("\n", '', $sLine);
	$aSingle = explode("\t", $sLine);
	//prt($aSingle);

	//write link
	//$sSingle = '{"source":'.$aSingle[0].',"target":'.$aSingle[1]."},\n";
	//fwrite($writeRs, $sSingle);

	$iSource = (int)$aSingle[0];
	$iTarget = (int)$aSingle[1];

	//add source node
	if(!array_key_exists($iSource, $aNodes)){

		//add node
		prt("add node #$iSource");

		$aNodes[$iSource] = array(	"node"=>$iSource,
						"name" => "node #$iCount",
						"inlink" => 0
						);
		$aNodesMap[] = $iSource;

		$iCount++;
	}

	//add target node
	if(!array_key_exists($iTarget, $aNodes)){

		//add node
		prt("add node #$iTarget");

		$aNodes[$iTarget] = array(	"node"=>$iTarget,
						"name" => "node #$iCount",
						"inlink" => 0
						);
		$aNodesMap[] = $iTarget;

		$iCount++;

	}

	//add link
	$aSingleLink = array(	"source"=> array_search($iSource, $aNodesMap) ,
				"target"=> array_search($iTarget, $aNodesMap) ,
				);
	$aLinks[] = $aSingleLink;
	prt("add link node #$iSource to node #$iTarget");

	//accumulate node in-link count
	$aNodes[$iTarget]["inlink"] = $aNodes[$iTarget]["inlink"]+1;

	if($iCount>300)
		break;

}

fclose($readRs);

//write
$writeRs = fopen("$sFile.json",'a');

if($writeRs == false){
	prt("destination open failed");
	exit;
}else{
	prt("destination open success");
}

$aResult = array();
$aResult["nodes"] = array_values($aNodes);
$aResult["links"] = $aLinks;

fwrite($writeRs, json_encode($aResult));
fclose($writeRs);

prt("done");

function prt($input){
	//echo '<pre>';
	print_r($input);
	print_r("\n");
	//echo '<pre>';
}

exit;
?>