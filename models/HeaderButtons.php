<?php

/**
 * Classe définissant les valeurs des boutons d'en-tête de la page monitoring
 */
class HeaderButtons
{
    private array $headerButtonsValues = [
        'title' => ['text' => '', 'state' => 'titleDesc', 'link' => 'index.php?action=monitoring&sort=titleDesc'],
        'views' => ['text' => '', 'state' => 'viewDesc', 'link' => 'index.php?action=monitoring&sort=viewDesc'],
        'comments' => ['text' => '', 'state' => 'comDesc', 'link' => 'index.php?action=monitoring&sort=comDesc'],
        'date' => ['text' => '', 'state' => 'dateDesc', 'link' => 'index.php?action=monitoring&sort=dateDesc']
    ];

    /**
     * Définit les valeurs par défaut des boutons d'en-tête
     * @param array $values
     * @return void
     */
    public function setHeaderButtonsValues(array $values): void
    {
        $this->headerButtonsValues = $values;
    }

    /**
     * Retourne les valeurs des boutons d'en-tête
     * @return array[]
     */
    public function getHeaderButtonsValues(): array
    {
        return $this->headerButtonsValues;
    }

    /**
     * Met à jour le lien et le texte indiquant le tri sur le bouton de la colonne triée
     * @param $sort
     * @return array[]
     */
    public function updateSortedColumnHeaderButton($sort): array
    {

        foreach ($this->headerButtonsValues as &$button) {
            if ($this->isDescendingSortButton($button, $sort)) {
                $button = $this->setButtonToDescOrder($button, $sort);

            } else if ($this->isAscendingSortButton($button, $sort)) {
                $button = $this->setButtonToAscOrder($button, $sort);
            }
        }
        return $this->headerButtonsValues;
    }

    /**
     * Retourne vraie si l'état du bouton correspond au paramètre sort de l'url pour un tri descendant (titleDesc, viewDesc...)
     * @param array $button
     * @param string $sort
     * @return bool
     */
    public function isDescendingSortButton(array $button, string $sort): bool
    {
        return substr($button['state'], 0, -4) == substr($sort, 0, -4);
    }

    /**
     *  Retourne vraie si l'état du bouton correspond au paramètre sort de l'url pour un tri ascendant (titleAsc, viewAsc...)
     * @param array $button
     * @param string $sort
     * @return bool
     */
    public function isAscendingSortButton(array $button, string $sort): bool
    {
        return substr($button['state'], 0, -4) == substr($sort, 0, -3);
    }

    /**
     * Définie le lien sur le prochain tri disponible pour cette colonne (Ascendant) et le texte d'indication
     * @param array $button
     * @param string $sort
     * @return array
     */
    public function setButtonToDescOrder(array $button, string $sort): array
    {
        $button['link'] = 'index.php?action=monitoring&sort=' . substr($sort, 0, -4) . 'Asc';
        $button['text'] = 'Tri descendant';

        return $button;
    }

    /**
     * Définie le lien sur le prochain tri disponible pour cette colonne (Descendant) et le texte d'indication
     * @param array $button
     * @param string $sort
     * @return array
     */
    public function setButtonToAscOrder(array $button, string $sort): array
    {
        $button['link'] = 'index.php?action=monitoring&sort=' . substr($sort, 0, -3) . 'Desc';
        $button['text'] = 'Tri ascendant';

        return $button;
    }

}