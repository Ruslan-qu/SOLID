<?php
/*Single Responsibility Principle (Принцип единственной ответственности).*/

/*Функция creatCounterparty в классе CounterpartyController выполняет слишком много задач, 
нарушая принцип единственной ответственности. Она не только создает контрагента, но и валидирует данные, 
управляет бизнес-логикой, сохраняет информацию в базе данных и отправляет уведомления.*/

/*Функция saveCounterparty в классе CounterpartyController строго следует принципу единственной ответственности. 
Она эффективно организует процесс создания контрагента, распределяя задачи между классами и функциями. 
Каждый элемент системы выполняет свою уникальную роль: валидация данных, управление бизнес-логикой, 
сохранение информации в базе данных и отправка уведомлений.*/

class CounterpartyController extends AbstractController
{
    public function creatCounterparty(
        ManagerRegistry $doctrine,
        Request $request,
        ValidatorInterface $validator
    ): Response {

        /* Подключаем сущности  */
        $entity_counterparty = new Counterparty();

        /* Подключаем форм */
        $form_counterparty = $this->createForm(CounterpartyType::class, $entity_counterparty);
        $form_counterparty->handleRequest($request);

        /* Подключаем валидацию*/
        $errors_counterparty = $validator->validate($form_counterparty);

        /*Валидация формы */
        if (
            $form_counterparty->isSubmitted()
        ) {
            if ($form_counterparty->isValid()) {

                $counterparty_strtolower_preg_replace = strtolower(preg_replace(
                    '#\s#',
                    '',
                    $request->request->all()['counterparty']['counterparty']
                ));


                $mail_counterparty_strtolower_preg_replace = strtolower(preg_replace(
                    '#\s#',
                    '',
                    $request->request->all()['counterparty']['mail_counterparty']
                ));

                /* Валидация дублей номеров деталей */
                $сount_counterparty = $doctrine->getRepository(Counterparty::class)
                    ->count(['counterparty' => $counterparty_strtolower_preg_replace]);

                $сount_mail_counterparty = $doctrine->getRepository(Counterparty::class)
                    ->count(['mail_counterparty' => $mail_counterparty_strtolower_preg_replace]);


                if ($сount_counterparty == 0) {

                    if ($сount_mail_counterparty == 0) {
                        /* Сохранение  в БД*/
                        $entity_counterparty->setCounterparty($counterparty_strtolower_preg_replace);

                        $entity_counterparty->setMailCounterparty($mail_counterparty_strtolower_preg_replace);


                        $em = $doctrine->getManager();
                        $em->persist($entity_counterparty);
                        $em->flush();
                    } else {

                        $this->addFlash('children[mail_counterparty].data_sales', 'Такой email существует');
                        $this->addFlash('mail_counterparty_sales', $mail_counterparty_strtolower_preg_replace);
                    }
                } else {

                    $this->addFlash('children[counterparty].data_sales', 'Такой поставщик существует');
                    $this->addFlash('counterparty_sales', $counterparty_strtolower_preg_replace);
                }

                return $this->redirectToRoute('counterparty');
            } else {

                /* Выводим вбитые данные в формы сохранения если форма не прошла валидацию, через сессии  */
                $value_form_counterparty = $request->request->all();
                if ($value_form_counterparty) {
                    foreach ($value_form_counterparty as $key => $values) {
                        if (is_iterable($values)) {
                            foreach ($values as $key => $value) {
                                $this->addFlash($key . '_sales', $value);
                            }
                        }
                    }
                }

                /* Выводим ошибки валидации, через сессии */
                if ($errors_counterparty) {
                    foreach ($errors_counterparty as $key) {
                        $message = $key->getmessage();
                        $propertyPath = $key->getpropertyPath() . '_sales';
                        $this->addFlash(
                            $propertyPath,
                            $message
                        );
                    }
                }

                return $this->redirectToRoute('counterparty');
            }
        }
    }


    public function saveCounterparty(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        SaveCounterpartyCommandHandler $saveCounterpartyCommandHandler
    ): Response {

        /*Форма сохранения постовщка*/
        $form_save_counterparty = $this->createForm(SaveCounterpartyType::class);
        $form_save_counterparty->handleRequest($request);

        $id = null;
        if ($form_save_counterparty->isSubmitted()) {
            if ($form_save_counterparty->isValid()) {

                try {
                    /*Запрос на аутентификацию пользователя*/
                    $participant = $adapterUserExtractionInterface->userExtraction();

                    $counterparty = $this->mapCounterparty(
                        null,
                        $form_save_counterparty->getData()['name_counterparty'],
                        $form_save_counterparty->getData()['mail_counterparty'],
                        $form_save_counterparty->getData()['manager_phone'],
                        $form_save_counterparty->getData()['delivery_phone'],
                        $participant
                    );
                    /*Сохранения постовщка*/
                    $id = $saveCounterpartyCommandHandler
                        ->handler(new CounterpartyCommand($counterparty));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        return $this->render('@counterparty/saveCounterparty.html.twig', [
            'title_logo' => 'Добавление нового поставщика',
            'form_save_counterparty' => $form_save_counterparty->createView(),
            'id_handler' => $id
        ]);
    }
}
