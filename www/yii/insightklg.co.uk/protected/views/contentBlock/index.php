<!-- To reverse the light/dark background flip BOTH the class and style below -->

<style>
divX {
width:400px;
height:25px;
background-color:#FFEEDD;
border: 1px solid #FF8855;
padding: 5px;
}
#div1 {
-moz-border-radius: 10px;
-webkit-border-radius: 10px;
border-radius: 10px;
}
</style>

<div id="div1" class="xxxBody-C" style="width:650px; background-color: #ffffff;padding:5px 15px">

  <?php echo $model->content; ?>

</div>