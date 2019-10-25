<?php


namespace App\Controller;


use App\Service\UploadHelper;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StatementController extends AbstractController
{

    /**
     * @Route(path="/newadmin/uploadstatement", name="uploadstatement")
     */
    public function uploadCsvStatement(Request $request, UploadHelper $helper)
    {
        /**
         * TODO add related user to the statement
         */
            if(!is_null($request->files->get('csv_file'))){

                try {

                    $file = $helper->uploadStatement($request);

                    $file['status'] === 'success' ? $this->addFlash('success', $file['message']) : $this->addFlash('error', $file['message']);

                    return $this->redirectToRoute('uploadstatement');

                } catch (Exception $e){

                    echo $e->getMessage();
                }
            }

        return $this->render('statement/uploadstatement.html.twig');

    }

    public function viewStatement()
    {

    }

    public function downloadStatement()
    {

    }

    public function searchStatement()
    {

    }

}