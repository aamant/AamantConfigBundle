<?php namespace Aamant\ConfigBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Config controller.
 */
class ConfigController extends Controller
{
    /**
     * Lists all Config entities.
     */
    public function indexAction(Request $request)
    {
        $configForm = $this->createForm('Aamant\ConfigBundle\Form\ConfigType');
        $configForm->handleRequest($request);

        if ($configForm->isSubmitted() && $configForm->isValid()) {
            $config = $this->get('aamant_config.config');
            foreach ($configForm->getData() as $key => $value){
                $config->set($key, $value);
            }

            return $this->redirectToRoute('aamant_config_admin');
        }

        return $this->render('AamantConfigBundle::config/index.html.twig', array(
            'config_form' => $configForm->createView(),
        ));
    }
}