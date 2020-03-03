<?php

namespace hcf\basefiles;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\CommandData;
use pocketmine\network\mcpe\protocol\types\CommandEnum;
use pocketmine\network\mcpe\protocol\types\CommandParameter;

abstract class CommandExecutor extends Command {

    /** @var CommandData */
    public $commandData;
    /** @var array */
    protected $commands = [];

    /**
     * CommandExecutor constructor.
     *
     * @param string $name
     * @param string $description
     * @param string|null $usageMessage
     * @param array $aliases
     * @param CommandParameter|null $parameter
     */

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [], CommandParameter $parameter = null){
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->commandData = new CommandData();
        $this->commandData->commandName = strtolower($this->getName());
        $this->commandData->commandDescription = $this->getDescription();
        $this->commandData->flags = 0;
        $this->commandData->permission = 0;
        if(!$parameter instanceof CommandParameter){
            $parameter = new CommandParameter();
            $parameter->paramName = 'args';
            $parameter->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_RAWTEXT;
            $parameter->isOptional = true;
            if($this->hasSubCommands()){
                //This helps to add the parameter to the command.
                $parameter->enum = new CommandEnum();
                $parameter->enum->enumName = ucfirst($this->getName()) . 'Aliases';
                $parameter->enum->enumValues = $this->toEnum();
            }
        }
        $this->addParameter($parameter);
        if(!empty($aliases)){
            if(!in_array($this->commandData->commandName, $aliases, true)){
                //work around a0 client bug which makes the original name not show when aliases are used
                $aliases[] = $this->commandData->commandName;
            }
            $this->commandData->aliases = new CommandEnum();
            $this->commandData->aliases->enumName = 'pet';
            $this->commandData->aliases->enumValues = $aliases;
        }
    }

    /**
     * @return bool
     */
    public function hasSubCommands(): bool {
        return count($this->commands) > 0;
    }

    /**
     * @return array
     */
    public function toEnum(): array {
        $commands = [];
        foreach($this->commands as $command){
            $commands[] = $command->getName();
        }
        return $commands;
    }

    /**
     * Adds parameter to overload
     *
     * @param CommandParameter $commandParameter
     * @param int $overloadIndex
     */

    public function addParameter(CommandParameter $commandParameter, int $overloadIndex = 0): void {
        $this->commandData->overloads[$overloadIndex][] = $commandParameter;
    }

    /**
     * Sets parameter to overload
     *
     * @param CommandParameter $parameter
     * @param int $parameterIndex
     * @param int $overloadIndex
     */

    public function setParameter(CommandParameter $parameter, int $parameterIndex, int $overloadIndex = 0) : void {
        $this->commandData->overloads[$overloadIndex][$parameterIndex] = $parameter;
    }

    /**
     * Sets parameter to overload
     *
     * @param array $parameters
     * @param int $overloadIndex
     */
    public function setParameters(array $parameters, int $overloadIndex = 0) : void {
        $this->commandData->overloads[$overloadIndex] = array_values($parameters);
    }

    /**
     * Removes parameter from overload
     *
     * @param int $parameterIndex
     * @param int $overloadIndex
     */
    public function removeParameter(int $parameterIndex, int $overloadIndex = 0) : void {
        unset($this->commandData->overloads[$overloadIndex][$parameterIndex]);
    }

    /**
     * Remove all overloads
     */
    public function removeAllParameters() : void{
        $this->commandData->overloads = [];
    }

    /**
     * Removes overload and includes.
     *
     * @param int $overloadIndex
     */
    public function removeOverload(int $overloadIndex) : void {
        unset($this->commandData->overloads[$overloadIndex]);
    }

    /**
     * Returns CommandData
     *
     * @return CommandData
     */
    public function getCommandData(): CommandData {
        return $this->commandData;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        $run = $this->onRun($sender, $args);
        if($run != null) $sender->sendMessage($run);
        return true;
    }

    /**
     * @param CommandSender $sender
     * @param array $args
     * @return string|null
     */
    public abstract function onRun(CommandSender $sender, array $args): ?string;
}
