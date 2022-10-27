<?php
/**
 * Index Controller
 */
class IndexController extends Controller
{
    /**
     * Process
     */
    public function process()
    {   
        header("HTTP/1.0 404 Not Found");
        $this->resp->result = 0;
        $this->resp->msg = "Unknown this path components";
        $this->jsonecho();
    }
}