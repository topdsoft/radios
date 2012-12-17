<?php
App::uses('AppController', 'Controller');
/**
 * Images Controller
 *
 * @property Image $Image
 */
class ImagesController extends AppController {

	public function upload($id=null) {
		if ($this->request->is('post')) {
			$this->Image->create();
if(empty($this->request->data['Images'])){debug($this->request->data);debug($_FILES);exit;}
			$imageSuccess=$imageFail=0;
			$failList=array();
			foreach($this->request->data['Images'] as $image) {
				//loop for all images uploaded
				$filetype = $image['type'];
				if (($filetype != "image/jpeg")  && ($filetype != "image/jpg") && ($filetype != "image/gif") && ($filetype != "image/png")) {
//					$this->Session->setFlash(__('Please choose a file of type:JPG,GIF or PNG.'));
					$imageFail++;
					$failList[]=$image['name'];
				} else {
					//filetype ok
//					if(!is_dir('img/'.$this->Auth->user('username'))) mkdir('img/'.$this->Auth->user('username'));
//					if(!is_dir('img/'.$this->Auth->user('username').'/thumbnails')) mkdir('img/'.$this->Auth->user('username').'/thumbnails',0770);
					$path='img/';//.$this->Auth->user('username').'/';
					//get next auto increment number to use in filename
					$q=$this->Image->query('SHOW TABLE STATUS where Name= "images"');
//debug($q);exit;
					$filename=$q[0]['TABLES']['Auto_increment'].str_replace(".", "", strtotime ("now"));
					//add ext
					if($filetype=='image/gif') $ext='.gif';
					else if($filetype=='image/png') $ext='.png';
					else $ext='.jpg';
					$tmpFile=$image['tmp_name'];
					if(is_uploaded_file($tmpFile)) {
						//file upload valid
						if($ext=='.jpg')$img_src=ImageCreateFromjpeg($tmpFile);
						else if($ext=='.png')$img_src=imagecreatefrompng($tmpFile);
						else $img_src=imagecreatefromgif($tmpFile);
						//create thumbnail
						$scale=50;
						$width = imagesx($img_src);
						$height = imagesy($img_src);
						$ratiox = $width / $height * $scale;
						$ratioy = $height / $width * $scale;
						//Calculate resampling
						$newheight = ($width <= $height) ? $ratioy : $scale;
						$newwidth = ($width <= $height) ? $scale : $ratiox;
						//Calculate cropping (division by zero)
						$cropx = ($newwidth - $scale != 0) ? ($newwidth - $scale) / 2 : 0;
						$cropy = ($newheight - $scale != 0) ? ($newheight - $scale) / 2 : 0;
						// Setup Resample & Crop buffers
						$resampled = imagecreatetruecolor($newwidth, $newheight);
						$cropped = imagecreatetruecolor($scale, $scale);
						//Resample
						imagecopyresampled($resampled, $img_src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
						//Crop
						imagecopy($cropped, $resampled, 0, 0, $cropx, $cropy, $newwidth, $newheight);
						// Save the cropped image
						if($ext=='.jpg')imagejpeg($cropped,$path.'thumbnails/'.$filename.$ext,80);
						else if($ext=='.png')imagepng($cropped,$path.'thumbnails/'.$filename.$ext);
						else imagegif($cropped,$path.'thumbnails/'.$filename.$ext);
						//check size of file
						$size=1200;
						if($width>$size || $height>$size) {
							//resize image
							$true_width = imagesx($img_src);
							$true_height = imagesy($img_src);

							if ($true_width>=$true_height) {
								$width=$size;
								$height = ($width/$true_width)*$true_height;
							} else {
								$height=$size;
								$width = ($height/$true_height)*$true_width;
							}//endif
							$cropped = ImageCreateTrueColor($width,$height);
							imagecopyresampled ($cropped, $img_src, 0, 0, 0, 0, $width, $height, $true_width, $true_height);
							// Save the cropped image
							if($ext=='.jpg')imagejpeg($cropped,$path.$filename.$ext,80);
							else if($ext=='.png')imagepng($cropped,$path.$filename.$ext);
							else imagegif($cropped,$path.$filename.$ext);
						} else {
							//no need to resize
							copy($tmpFile,$path.$filename.$ext);
						}//endif 
						$this->request->data['Image']['filename']=$filename.$ext;
						$this->request->data['Image']['item_id']=$id;
						$this->request->data['Image']['id']=null;
//debug($this->request->data);//debug($filename);exit;
						if ($this->Image->save($this->request->data)) {
							$imageSuccess++;
						} else {
							$imageFail++;
							$failList[]=$image['name'];
						}
					} else {
						//not uploaded file
						$imageFail++;
						$failList[]=$image['name'];
					}//endif check for uploaded file
				}//endif filetype
			}//end foreach loop for all files uploaded
			if($imageSuccess==0 && $imageFail==0) $this->Session->setFlash(__('No Image selected. Please, try again.'));
			else if ($imageFail>0) {
				//some files not ok
				$msg="$imageSuccess file";
				if($imageSuccess!=1) $msg.='s';
				$msg.=" were uploaded.  $imageFail file";
				if($imageFail>1) $msg.='s';
				$msg.=" failed to upload.  Failed file";
				if($imageFail>1) $msg.='s';
				$msg.=':';
				foreach($failList as $fail) $msg.=' '.$fail;
				$this->Session->setFlash($msg);
			} else {
				//all good
				$msg="$imageSuccess file";
				if($imageSuccess!=1) $msg.='s';
				$msg.=' Uploaded Successfully';
				$this->Session->setFlash($msg,'default',array('class'=>'success'));
				if(isset($this->request->data['in']['redirect'])) $this->redirect($this->request->data['in']['redirect']);
				$this->redirect(array('controller'=>'items','action' => 'edit',$id));
			}
		} else {
/*			//get referer info
			$exp=explode('/',$this->referer());
			if(count($exp)>3)$controller=$exp[count($exp)-3];
			else $controller=null;
			if($specific && $controller=='locos') {
				//assign image to loco
				$this->request->data['Loco']['Loco'][0]=$exp[count($exp)-1];
				$this->set('loco',$this->Image->Loco->read(null,$exp[count($exp)-1]));
			}//endif
			if($specific && $controller=='cars'){
				//assign image to car
				$this->request->data['Car']['Car'][0]=$exp[count($exp)-1];
				$this->set('car',$this->Image->Car->read(null,$exp[count($exp)-1]));
			}//endif
			$this->request->data['in']['redirect']=$this->referer(array('action'=>'index'));
//			debug($exp);exit;*/
		}
		
	}
}
