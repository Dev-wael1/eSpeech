<?php
    namespace App\Controllers\user;

    use App\Controllers\BaseController;

    class Text_To_Speech extends User
    {
        public function __construct()
        {
            parent::__construct();
        }
        public function index()
        {
            if($this->isLoggedIn)
            {
                $this->data['tags'] = fetch_details('ssml_tags');
                $this->data['title'] = 'Text to Speech';
                $this->data['main_page'] = '../../text_to_speech';
                $this->data['languages'] = fetch_details('tts_languages', ['status' => '1']);
                return view('backend/user/template', $this->data);
            }
            else
            {
                return redirect('unauthorised');
            }
        }
    }
?>