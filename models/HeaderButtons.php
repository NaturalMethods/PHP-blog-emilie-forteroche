<?php

/**
 * Classe définissant les valeurs des boutons d'en-tête de la page monitoring
 */
class HeaderButtons
{
    private array $headerButtonsValues = [
        'title' => ['text' => '', 'shortname' => 'tit', 'link' => 'index.php?action=monitoring&sort=titDes'],
        'views' => ['text' => '', 'shortname' => 'vie', 'link' => 'index.php?action=monitoring&sort=vieDes'],
        'comments' => ['text' => '', 'shortname' => 'com', 'link' => 'index.php?action=monitoring&sort=comDes'],
        'date' => ['text' => '', 'shortname' => 'dat', 'link' => 'index.php?action=monitoring&sort=datDes']
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
    public function updateSortedColumnHeaderButton(string $sort): array
    {
        foreach ($this->headerButtonsValues as &$button) {

            $sortedColumn = $this->getShortNameFromParameter($sort);
            $typeOfSort = $this->getSortFromParameter($sort);

            if ($this->isButtonTheSortedColumn($button, $sortedColumn)) {
                $button = $this->setButtonSortDatas($typeOfSort, $button);
            }
        }
        return $this->headerButtonsValues;
    }

    /**
     * Retourne le bouton avec les bonnes informations de tri (ascendant| descendant)
     * @param String $typeOfSort
     * @param array $button
     * @return array
     */
    public function setButtonSortDatas(String $typeOfSort, array $button): array
    {
        if ($this->isDescendingSort($typeOfSort))
            return $this->setButtonToDescOrder($button);

        else if ($this->isAscendingSort($typeOfSort))
            return $this->setButtonToAscOrder($button);

        return $button;

    }

    /**
     * Retourne le shortname contenu dans le paramètre sort
     * @param string $sort
     * @return string
     */
    public function getShortNameFromParameter(string $sort): string
    {
        return substr($sort, 0, -3);
    }

    /**
     * Retourne le sens du tri contenu dans le paramètre sort
     * @param string $sort
     * @return string
     */
    public function getSortFromParameter(string $sort): string
    {
        return substr($sort, 3);
    }

    /**
     *  Retourne vraie si le nom raccourci du bouton est égal à celui du paramètre sort
     * @param array $button
     * @param string $sortedColumn
     * @return bool
     */
    public function isButtonTheSortedColumn(array $button ,string $sortedColumn): bool
    {
        return strcasecmp($button['shortname'], $sortedColumn) === 0;
    }

    /**
     * Retourne vraie si le type de tri est descendant
     * @param string $typeOfSort
     * @return bool
     */
    public function isDescendingSort(string $typeOfSort): bool
    {
        return strcasecmp($typeOfSort, "Des") === 0;
    }

    /**
     *  Retourne vraie si le type de tri est ascendant
     * @param string $typeOfSort
     * @return bool
     */
    public function isAscendingSort(string $typeOfSort): bool
    {
        return strcasecmp($typeOfSort, "Asc") === 0;
    }

    /**
     * Définie le lien sur le prochain tri disponible pour cette colonne (Ascendant) et le texte d'indication
     * @param array $button
     * @param string $sort
     * @return array
     */
    public function setButtonToDescOrder(array $button): array
    {
        $button['link'] = 'index.php?action=monitoring&sort=' . $button['shortname'] . 'Asc';
        $button['text'] = '▼';

        return $button;
    }

    /**
     * Définie le lien sur le prochain tri disponible pour cette colonne (Descendant) et le texte d'indication
     * @param array $button
     * @param string $sort
     * @return array
     */
    public function setButtonToAscOrder(array $button): array
    {
        $button['link'] = 'index.php?action=monitoring&sort=' . $button['shortname'] . 'Des';
        $button['text'] = '▲';

        return $button;
    }

}