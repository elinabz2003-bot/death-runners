<?php

namespace App\Classes;

/**
 * Classe ChatbotMessage
 *
 * Représente un message échangé dans votre système de chat 
 * (IA ou joueur), stocké en base dans la table `chatbot`.
 */
class ChatbotMessage
{
    private ?int $id_message;
    private ?int $id_user;    
    private string $sender;   
    private string $message;
    private string $date_envoi;

    /**
     * Constructeur
     *
     * @param int|null $id_message
     * @param int|null $id_user
     * @param string   $sender
     * @param string   $message
     * @param string   $date_envoi
     */
    public function __construct(
        ?int $id_message,
        ?int $id_user,
        string $sender,
        string $message,
        string $date_envoi
    ) {
        $this->id_message = $id_message;
        $this->id_user    = $id_user;
        $this->sender     = $sender;
        $this->message    = $message;
        $this->date_envoi = $date_envoi;
    }

    public function __toString(): string
    {
        return "[ChatMessage #{$this->id_message}] {$this->sender}: {$this->message}";
    }

    
    public function getIdMessage(): ?int
    {
        return $this->id_message;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getDateEnvoi(): string
    {
        return $this->date_envoi;
    }

    
    public function setSender(string $sender): void
    {
        $this->sender = $sender;
    }

    public function setMessage(string $msg): void
    {
        $this->message = $msg;
    }

    public function setDateEnvoi(string $date): void
    {
        $this->date_envoi = $date;
    }
}
