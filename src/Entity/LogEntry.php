<?php

namespace AcePedigree\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Loggable\Entity\Repository\LogEntryRepository")
 * @ORM\Table(name="pedigree_logentry")
 */
class LogEntry extends AbstractLogEntry
{ }
