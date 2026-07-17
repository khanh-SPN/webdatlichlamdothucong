<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * CompanyInfos Controller
 *
 * @property \App\Model\Table\CompanyInfosTable $CompanyInfos
 */
class CompanyInfosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->CompanyInfos->find();
        $companyInfos = $this->paginate($query);

        $this->set(compact('companyInfos'));
    }

    /**
     * View method
     *
     * @param string|null $id Company Info id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $companyInfo = $this->CompanyInfos->get($id, contain: []);
        $this->set(compact('companyInfo'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $companyInfo = $this->CompanyInfos->newEmptyEntity();
        if ($this->request->is('post')) {
            $companyInfo = $this->CompanyInfos->patchEntity($companyInfo, $this->request->getData());
            if ($this->CompanyInfos->save($companyInfo)) {
                $this->Flash->success(__('The company info has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The company info could not be saved. Please, try again.'));
        }
        $this->set(compact('companyInfo'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Company Info id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $companyInfo = $this->CompanyInfos->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $companyInfo = $this->CompanyInfos->patchEntity($companyInfo, $this->request->getData());
            if ($this->CompanyInfos->save($companyInfo)) {
                $this->Flash->success(__('The company info has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The company info could not be saved. Please, try again.'));
        }
        $this->set(compact('companyInfo'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Company Info id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $companyInfo = $this->CompanyInfos->get($id);
        if ($this->CompanyInfos->delete($companyInfo)) {
            $this->Flash->success(__('The company info has been deleted.'));
        } else {
            $this->Flash->error(__('The company info could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
