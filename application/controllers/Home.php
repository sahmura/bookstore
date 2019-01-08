<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('Model_akun');

    }

    public function index()
    {
        $data['judul'] = "LL Store";
        $this->load->view('templates/header', $data);
        $this->load->view('home/slide');
        $this->load->view('home/topseller');
        $this->load->view('home/newadded');
        $this->load->view('home/kategori');
        $this->load->view('templates/footer');
    }

    public function login()
    {
        $data['judul'] = "Login";

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == false) {

            $this->load->view('templates/header', $data);
            $this->load->view('home/login');
            $this->load->view('templates/footer');

        } else {

            $pengguna = $this->input->post('username');
            $pass = md5($this->input->post('password'));
            $cek = $this->Model_akun->validasiLogin($pengguna, $pass);
            $admin = $this->Model_akun->displayAkun($pengguna);
            $ya = $admin['is_admin'];

            if ($cek == true) {

                $login = [
                    "user" => $pengguna,
                    "log" => "logged",
                    "admin" => $ya
                ];

                $this->session->set_userdata($login);
                $this->session->set_flashdata('sukseslogin', 'Selamat datang di toko kami!');
                redirect('home');
            }

            if ($cek == false) {
                $this->session->set_flashdata('erorlogin', 'Username atau Password salah');
                redirect('home/login');
            }
        }


    }

    public function register()
    {
        $data['judul'] = "Register";

        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[4]|alpha_dash');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('ulangipassword', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == false) {

            $this->load->view('templates/header', $data);
            $this->load->view('home/daftar');
            $this->load->view('templates/footer');

        } else {

            $this->Model_akun->insertNewAkun();
            $this->session->set_flashdata('sukses', 'Silahkan masuk untuk mulai menggunakan aplikasi');
            redirect('home/login');

        }


    }

    public function logout()
    {
        $this->Model_akun->logout();
        redirect('home/index');
    }

}
