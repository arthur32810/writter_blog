<?php $title = 'Mon Blog !'; ?>

<?php ob_start(); ?>

		<h1> Mon super blog ! </h1>
		<h2> Derniers billets du blog : </h2>
	
	<?php		
		while($data = $posts->fetch())
		{ ?> 
	
			 <div class="news">
                <h3>
                    <?= htmlspecialchars($data['title']) ?>
                    <em>le <?= $data['creation_date_fr'] ?></em>
                </h3>
                
                <p>
                    <?= nl2br(htmlspecialchars($data['content'])) ?>
                    <br />
                    <em><a href="index.php?action=post&&id=<?= $data['id']?>"> Lire le chapitre</a></em>
                </p>
            </div>
			
		<?php }
		
		$posts->closeCursor();	?>

		<div style="text-align: center;"> <?php

			for ($i=1; $i<= $nb_paging_posts; $i++) { ?>

					<a href="index.php?action=listPosts&post_page=<?= $i ?>"> <?= $i ?> </a> 

			<?php }	?>
		</div>

<?php $content = ob_get_clean(); ?>

<?php require('view/template.php');?>