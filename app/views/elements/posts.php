<style>
div.test_ > * ,
div.test_ > * > *{
	border:1px solid red;
	margin:10px;
}
</style>
<?php if(count($posts) > 0){?>
	<?php foreach($posts as $post){?>
		<div class="row test">
			<div class="row">
				<div class="span"><h3><?php echo $this->html->linkTo($post["title"],$post["urlfriendly"],'rel="bookmark" title="Enlace a '.$post["title"].'"'); ?></h3></div>
				<div class="span facebook"><fb:like href="http://mis-algoritmos.com/<?php echo $post['urlfriendly']; ?>" layout="button_count"></fb:like></div>
			</div>

			<div class="row">
				<?php echo $post["content"]; ?>
			</div>
			
			
			<?php if(isset($busqueda) === false){ ?>
					<p><?php echo $this->html->linkTo($post["comments_count"],"{$post["urlfriendly"]}#comments",'rel="bookmark" title="Comentarios de '.$post["title"].'"'); ?> &#187;</p>
					
					<p><?php if($post["tags"]){ ?>
						<p class="tags">
							Tags:
							<?php foreach($post["tags"] as $tag){ ?>
								<?php echo $this->html->linkTo($tag["tag"],"tag/{$tag["urlfriendly"]}"); ?>
							<?php }//foreach ?>
							<br />
						</p>
					<?php } ?></p>
				
					<p>Escrito por <?php echo $post["autor"]["name"]; ?> el <?php echo $post["created"]?></p>
				
			<?php }else{ ?>

			<?php } ?>

		</div>
	<?php } ?>
	<?php echo $pagination; ?>
<?php }else{?>
	<h2 class="center">Your search did not match any documents.</h2>
<?php } ?>
