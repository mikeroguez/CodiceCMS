<?php

class tag_controller extends appcontroller {
	
	public function __construct() {
		parent::__construct();
		
		$this->plugin->call('codice_init');
	}
	
	
	public function __call($method, $args){
		$page = (int) (isset($args[0])?$args[0]:1);
		if($page)
			$this->index($method, $page);
		else
			$this->redirect($this->config["blog"]['blog_siteurl']);
	}
	
	public function beforeRender(){
		
		$link = new link();
		$this->view->links = $link->findAllBy("type","external");//links para el sidebar
	}
	
	
	public function index($tag=NULL, $page=1){
		if(is_null($tag)/* or !preg_match("/^[a-z\-\_]]$/i",$tag) */){
			die('redirect');
			$this->redirect($this->config["blog"]['blog_siteurl']);
		}
		
		$post = new post();
		$link = new link();
		$comment = new comment();

//		$this->html->useTheme($this->config["blog"]['blog_current_theme']);

//		$isAdmin = false;
/*		if($this->cookie->check("logged") and $this->cookie->id_user == 1){//is Admin logged?
			$isAdmin = true;
		}
*/
//		$this->views->isAdmin = $isAdmin;

		$includes['charset'] = $this->html->charsetTag("UTF-8");
		$includes['rssFeed'] = $this->html->includeRSS();

		if($page>1){
			$includes['canonical'] = "<link rel=\"canonical\" href=\"{$this->config["blog"]['blog_siteurl']}/tag/".rawurlencode($tag)."/$page\" />";
		}else{
			$includes['canonical'] = "<link rel=\"canonical\" href=\"{$this->config["blog"]['blog_siteurl']}/tag/".rawurlencode($tag)."\" />";
		}


//		$this->registry->includes = $includes;
//		$this->plugin->call('index_includes');

		$strIncludes = "";
		foreach($includes as $include){
			echo $include;
			$strIncludes .= $include;
		}
		$this->view->includes = $strIncludes;
		
//		$this->themes->links = $link->findAll();
//		$this->themes->single = false;
		$total_rows = $post->countPosts(array('status'=>'Publish','tag'=>$tag));

		$page = (int) (is_null($page)) ? 1 : $page;
		$limit = $this->config["blog"]['blog_posts_per_page'];
		$offset = (($page-1) * $limit);
		$limitQuery = $offset.",".$limit;
		$targetpage = $this->path."tag/$tag/";

		$this->view->pagination = $this->pagination->init($total_rows, $page, $limit, $targetpage);
		

		$posts = $post->getPostsByTag($tag,$limitQuery);

		foreach($posts as $k=>$p){
			$posts[$k]['title'] = htmlspecialchars($p['title']);
			$posts[$k]['tags'] = $post->getTags($p['idPost']);
			$posts[$k]['comments_count'] = $comment->countCommentsByPost($posts[$k]['idPost']);

			$user = new user();
			if($posts[$k]['idUser']<2){
				$posts[$k]['autor'] = $user->find(1);
			}else{
				$posts[$k]['autor'] = $user->find($posts[$k]['id_user']);
			}
		}

		$this->view->posts = $posts;
		$this->view->tag = $tag;
//		$this->plugin->call("index_post_content");
//		$this->themes->posts = $this->registry->posts;

		$this->title_for_layout("{$this->config["blog"]['blog_name']} - $tag");
	
		$this->view->setLayout("codice");
		$this->render('index');
	}
}
