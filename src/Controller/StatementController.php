<?php


namespace App\Controller;


use App\Entity\StatementFile;
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
            if(!is_null($request->files->get('csv_file'))){

                try {

                    $file = $helper->uploadStatement($request);

                    $file['status'] === 'success' ? $this->addFlash('success', $file['message']) : $this->addFlash('error', $file['message']);

                    return $this->redirectToRoute('uploadstatement');

                } catch (Exception $e){

                    echo $e->getMessage();
                }
            }

            $data = $this->getDoctrine()
                        ->getRepository(StatementFile::class)
                        ->lastUploadedPerUserAndAccount();

        return $this->render('statement/uploadstatement.html.twig', [
            'data' => $data
        ]);

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