<!-- <style>
body{
display:none !important;
}
</style>
<script>
alert("Permission Denied, You don't have the access to Edit/Modify this complaint !");
window.close();
</script>
-->
<rn:meta title="#rn:msg:ERROR_LBL#" template="standard.php" login_required="false" />

<?list($errorTitle, $errorMessage) = \RightNow\Utils\Framework::getErrorMessageFromCode(\RightNow\Utils\Url::getParameter('error_id'));?>
<div class="rn_Hero">
    <div class="rn_Container" style='height:225px'>
        <h1><?=$errorTitle;?></h1>
        <br>
    </div>
</div>
<div class="rn_PageContent rn_ErrorPage rn_Container">
    <h2 style='color:red'><?=$errorMessage;?></h2>
</div>
